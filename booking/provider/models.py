from datetime import datetime, timedelta

from django.contrib.auth.models import User
from django.db import models
from django.utils.text import slugify
from timezone_field import TimeZoneField
from typing import List

from agenda.models import Event

from djradicale.models import DBCollection


class Provider(models.Model):
    name = models.CharField(u'Nom', default='', max_length=100)
    phone = models.CharField(u'Numero de Telephone', default='', max_length=20)
    email = models.CharField(u'Email', default='', max_length=50)
    address = models.CharField(u'Adresse', default='', max_length=50)
    code = models.CharField(u'NPA/Zip Code', default='', max_length=10)
    city = models.CharField(u'Ville', null=True, blank=True, max_length=50)
    country = models.CharField(u'Pays', null=True, blank=True, max_length=50)
    timezone = TimeZoneField(default='Europe/Zurich')
    slug = models.SlugField(u'Slug', editable=False, default='', max_length=200)

    def save(self, *args, **kwargs):
        self.slug = slugify(self.name, allow_unicode=True)
        super().save(*args, **kwargs)

    def ordered_schedule_set(self):
        return self.providerschedule_set.all()

    def get_schedule(self, day_id):
        return self.providerschedule_set.filter(day_id=day_id)

    def __unicode__(self):
        return self.name

    def __str__(self):
        return self.name

    class Meta:
        verbose_name = u'Provider'


class ProviderSchedule(models.Model):
    DAY_ID = {'Monday': 0, 'Tuesday': 1, 'Wednesday': 2, 'Thursday': 3, 'Friday': 4, 'Saturday': 5, 'Sunday': 6}

    provider = models.ForeignKey(Provider, on_delete=models.DO_NOTHING)
    day = models.CharField(u'Jours', default='', max_length=10)
    day_id = models.IntegerField(u'Identifiant du Jour - Lundi 0, Dimanche 6', default=0)
    open_time = models.TimeField(verbose_name='Heure d\'ouverture', null=True, blank=True)
    closed_time = models.TimeField(verbose_name='Heure de fermeture', null=True, blank=True)
    is_closed = models.BooleanField(default=False, verbose_name='Fermé')

    def __unicode__(self):
        return self.day

    class Meta:
        verbose_name = u'Heure d\'ouverture'


class ProviderService(models.Model):
    name = models.CharField('Type de Services', max_length=100)
    duration = models.TimeField('Durée (minutes)', default=None)
    provider = models.ForeignKey(Provider, on_delete=models.DO_NOTHING, null=True)

    def __unicode__(self):
        return self.name

    def __str__(self):
        return self.name

    class Meta:
        verbose_name = u'Service'
        verbose_name_plural = u'Services'


class Person(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE, null=True)
    name = models.CharField(u'Nom', default='', max_length=50)
    description = models.TextField(u'Description', default='', max_length=250)
    provider = models.ForeignKey(Provider, on_delete=models.DO_NOTHING)
    timezone = TimeZoneField(default='Europe/Zurich')
    calendar = models.ForeignKey(DBCollection, on_delete=models.CASCADE, null=True)
    is_active = models.BooleanField(u'Rendez-vous activé', default=False)


    def availabilities_for_service_one_day(self, service, day):
        day = datetime.strptime(day, "%Y-%m-%d")

        arr_availabilities = []
        provider_schedule = self.provider.get_schedule(ProviderSchedule.DAY_ID[day.strftime("%A")]).first()
        if not provider_schedule.is_closed:
            closing_hour = provider_schedule.closed_time
            closing_hour = datetime(day.year, day.month, day.day, closing_hour.hour,
                                    closing_hour.minute, closing_hour.second)
            opening_hour = provider_schedule.open_time
            opening_hour = datetime(day.year, day.month, day.day, opening_hour.hour,
                                    opening_hour.minute, opening_hour.second)
            events = DBCollection.get_events(self.calendar, day, day)

            while opening_hour <= closing_hour:
                start_hour = datetime(day.year, day.month, day.day, opening_hour.hour,
                                      opening_hour.minute, opening_hour.second)
                end_hour = start_hour + timedelta(hours=service.duration.hour, minutes=service.duration.minute)
                if start_hour + timedelta(hours=service.duration.hour, minutes=service.duration.minute) <= closing_hour:
                    if is_available(start_hour, end_hour, events):
                        id_children = "%02d-%02d-%dT%02d:%02d" % (day.day, day.month, day.year, opening_hour.hour,
                                                                  opening_hour.minute)
                        text_children = "%02d:%02d" % (opening_hour.hour, opening_hour.minute)
                        arr_availabilities.append(text_children)
                opening_hour += timedelta(hours=service.duration.hour, minutes=service.duration.minute)

        return arr_availabilities

    def availabilities_for_service(self, service, start_day, end_day=1):
        start_day_or = datetime.strptime(start_day, "%Y-%m-%d")
        start_day = start_day_or
        end_day = start_day + timedelta(days=end_day)
        availability_found = False
        arr_results = []
        events = DBCollection.get_events(self.calendar, start_day, end_day)
        delta_day = timedelta(days=1)

        while start_day <= end_day:
            arr_availabilities = []
            provider_schedule = self.provider.get_schedule(ProviderSchedule.DAY_ID[start_day.strftime("%A")]).first()
            text_group = "%02d-%02d-%d" % (start_day.day, start_day.month, start_day.year)

            if not provider_schedule.is_closed:
                closing_hour = provider_schedule.closed_time
                closing_hour = datetime(start_day.year, start_day.month, start_day.day, closing_hour.hour,
                                        closing_hour.minute, closing_hour.second)
                opening_hour = provider_schedule.open_time
                opening_hour = datetime(start_day.year, start_day.month, start_day.day, opening_hour.hour,
                                        opening_hour.minute, opening_hour.second)

                while opening_hour <= closing_hour:
                    start_hour = datetime(start_day.year, start_day.month, start_day.day, opening_hour.hour,
                                          opening_hour.minute, opening_hour.second)
                    end_hour = start_hour + timedelta(hours=service.duration.hour, minutes=service.duration.minute)

                    if start_hour + timedelta(hours=service.duration.hour,
                                              minutes=service.duration.minute) <= closing_hour:
                        if is_available(start_hour, end_hour, events):
                            id_children = "%02d-%02d-%dT%02d:%02d" % (start_day.day, start_day.month,
                                                                      start_day.year, opening_hour.hour,
                                                                      opening_hour.minute)

                            text_children = "%02d:%02d" % (opening_hour.hour, opening_hour.minute)

                            arr_availabilities.append({"id": id_children, "text": text_children})

                    opening_hour += timedelta(hours=service.duration.hour, minutes=service.duration.minute)

                arr_results.append({"text": text_group, "children": arr_availabilities})

                # if we found the first availability continue to the next day
                if availability_found:
                    availability_found = False
                    start_day += delta_day
                    continue

            start_day += delta_day

        return arr_results

    def __unicode__(self):
        return self.name

    def __str__(self):
        return self.name

    class Meta:
        verbose_name = 'Person'


def is_available(start_hour: datetime, end_hour: datetime, events: List[Event]) -> bool:
    a = True

    if len(events) != 0:
        for e in events:
            # if event date equal the time range with test
            if start_hour.date() == e.event_date.date():
                # if event covers the range we test
                if (start_hour.time() > e.start_time) and (end_hour.time() < e.end_time):
                    a = False
                    break
                elif (e.start_time >= start_hour.time()) and (e.start_time <= end_hour.time()) and (
                        e.end_time >= start_hour.time()) and (e.end_time <= end_hour.time()):
                    a = False
                    break
                elif (e.start_time <= start_hour.time()) and (e.end_time >= end_hour.time()):
                    a = False
                    break
                elif (e.start_time > start_hour.time()) and (e.start_time < end_hour.time()) and (
                        e.end_time > end_hour.time()):
                    a = False
                    break
                elif (e.start_time < start_hour.time()) and (
                        e.end_time < end_hour.time() and (e.end_time > start_hour.time())):
                    a = False
                    break

    return a
