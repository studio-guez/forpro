import uuid
from datetime import datetime

from django.db import models
from django.db.models.signals import post_save, post_init
from django.utils.translation import ugettext_lazy as _
from django.utils.translation import ugettext
from django.utils.crypto import get_random_string
from django.contrib.auth.models import User

from djradicale.models import DBCollection, DBItem


class Event(models.Model):

    @classmethod
    def create(cls, event_date, start_time, end_time, author, title, description,
               timezone, collection, published=False):

        # CLEAN
        slug = get_random_string(length=32)
        publish_date = datetime.now().replace(tzinfo=timezone)

        event = cls(event_date=event_date, start_time=start_time, end_time=end_time, author=author,
                    title=title, description=description,
                    publish=published, slug=slug, publish_date=publish_date, state=False, collection=collection)
        return event

    class Meta:
        verbose_name = _('Rendez-Vous')
        verbose_name_plural = _('Rendez-Vous')
        ordering = ['-event_date', '-start_time', '-title']
        get_latest_by = 'event_date'
        permissions = (("change_author", ugettext("Change author")),)

    def __unicode__(self):
        return _("%(title)s on %(event_date)s") % {'title': self.title,
                                                   'event_date': self.event_date}

    objects = models.Manager()

    # Core fields
    title = models.CharField(_('Nom, Prénom du Client'), max_length=255)
    slug = models.SlugField(_('slug'), db_index=True)

    state = models.BooleanField('Etat', default=False)
    previous_state = None

    event_date = models.DateField(_('date'))

    start_time = models.TimeField(_('start time'), blank=True, null=True)
    end_time = models.TimeField(_('end time'), blank=True, null=True)

    description = models.TextField(_('description'))

    add_date = models.DateTimeField(_('add date'), auto_now_add=True)
    mod_date = models.DateTimeField(_('modification date'), auto_now=True)

    author = models.CharField(_('Email Client'), blank=True, null=True, max_length=150)

    publish_date = models.DateTimeField(_('publication date'), default=datetime(2018, 3, 1, 20, 13, 56, 213157))
    publish = models.BooleanField(_('publish'), default=True)

    allow_comments = models.BooleanField(_('Allow comments'), default=True)

    collection = models.ForeignKey(DBCollection, on_delete=models.DO_NOTHING, null=True)

    @staticmethod
    def add_to_calendar_if_confirmed(sender, **kwargs):
        instance = kwargs.get('instance')
        created = kwargs.get('created')
        if instance.previous_state != instance.publish:
            from icalendar import Event, Calendar, vDatetime, vText
            # Create Event
            cal = Calendar()
            cal.add('prodid', '-//Radicale//NONSGML Radicale Server//EN')
            cal.add('version', '2.0')

            event = Event()
            uid = str(uuid.uuid4())
            event.add('summary', instance.title)
            event.add('location', instance.description)
            event.add('dtstart', vDatetime(datetime.combine(instance.event_date, instance.start_time)).dt)
            event.add('dtend', vDatetime(datetime.combine(instance.event_date, instance.end_time)).dt)
            event.add('dtstamp', instance.publish_date)
            event.add('uid', uid)
            name = uid + '.ics'
            event.add('X-RADICALE-NAME', name)

            cal.add_component(event)
            text = cal.to_ical().decode('utf-8').replace('\r\n', '\n').strip()
            i = DBItem(collection=DBCollection.objects.get(pk=instance.collection.pk), name=name,
                       text=text)
            i.save()

    @staticmethod
    def remember_state(sender, **kwargs):
        instance = kwargs.get('instance')
        instance.previous_state = instance.publish


post_save.connect(Event.add_to_calendar_if_confirmed, sender=Event)
post_init.connect(Event.remember_state, sender=Event)
