# -*- coding: utf-8 -*-

from datetime import datetime, timedelta

from django.contrib.auth.models import User
from django.db import models
from django.utils.text import slugify
from timezone_field import TimeZoneField
from typing import List

from agenda.models import Event, EventCategory
from django_google_maps import fields as map_fields
from djradicale.models import DBCollection


class Slider(models.Model):
    name = models.CharField(u'Nom', default='', max_length=25)
    title = models.CharField(u'Titre', default='', max_length=50)
    subtitle = models.CharField(u'Sous-Titres', default='', max_length=50)
    picture = models.ImageField(u'Image - 2000x1125')
    call_to_action = models.CharField(u'Call to Action', default='', max_length=50)
    link = models.CharField(u'Lien Interne du bouton', default='', max_length=25)
    order = models.IntegerField(u'Position', default=0)

    def __unicode__(self):
        return self.title

    def __str__(self):
        return self.title

    class Meta:
        verbose_name = u'Slider'


class Country(models.Model):
    name = models.CharField(u'Pays', default='', max_length=25)
    code = models.CharField(u'Code', default='', max_length=5)
    slug = models.SlugField(u'Slug', editable=False, default='', max_length=100)

    def save(self, *args, **kwargs):
        self.slug = slugify(self.name, allow_unicode=True)
        super().save(*args, **kwargs)

    def __unicode__(self):
        return self.name

    def __str__(self):
        return self.name

    class Meta:
        verbose_name = u'Pays'


class City(models.Model):
    name = models.CharField(u'Ville', default='', max_length=20)
    country = models.ForeignKey(Country, default=4, null=True, blank=True, on_delete=models.DO_NOTHING, max_length=25)
    slug = models.SlugField(u'Slug', editable=False, max_length=40)

    def save(self, *args, **kwargs):
        self.slug = slugify(self.name, allow_unicode=True)
        super().save(*args, **kwargs)

    def get_country(self):
        return self.country.name

    def __unicode__(self):
        return self.name

    def __str__(self):
        return self.name

    class Meta:
        verbose_name = u'Ville'


class Barbershop(models.Model):
    name = models.CharField(u'Nom du salon', default='', max_length=100)
    description = models.TextField(u'Description', default='', max_length=250)
    phone = models.CharField(u'Numero de Telephone', default='', max_length=20)
    email = models.CharField(u'Email', default='', max_length=50)
    address = models.CharField(u'Adresse', default='', max_length=50)
    code = models.CharField(u'NPA/Zip Code', default='', max_length=10)
    city = models.ForeignKey(City, null=True, blank=True, on_delete=models.DO_NOTHING)
    country = models.ForeignKey(Country, default=2, on_delete=models.DO_NOTHING)
    geolocation = map_fields.GeoLocationField(u'Coordonées', max_length=100, default='')
    color = models.CharField(u'Couleur', default='', max_length=10)
    form_url = models.CharField(max_length=255, default='')
    picture = models.ImageField(u'Photo du salon - 600 x 1000', default='')
    picture_appointment = models.ImageField(u'Photo rendez-vous - 1000 x 686', default='')
    banner = models.ImageField(u'Bannière - 1920 x 1080', default='')
    meta_title = models.CharField(u'Titre', default='', max_length=60)
    meta_description = models.TextField(u'Description', default='', max_length=140)
    meta_tag = models.CharField(u'Tag', default='', max_length=140)
    timezone = TimeZoneField(default='Europe/Zurich')
    slug = models.SlugField(u'Slug', editable=False, default='', max_length=200)

    def save(self, *args, **kwargs):
        self.slug = slugify(self.name, allow_unicode=True)
        super().save(*args, **kwargs)

    def ordered_schedule_set(self):
        return self.schedule_set.all()

    def get_schedule(self, day_id):
        return self.schedule_set.all().filter(day_id__exact=day_id)

    def __unicode__(self):
        return self.name

    def __str__(self):
        return self.name

    class Meta:
        verbose_name = u'Barbershop'


class Schedule(models.Model):
    DAY_ID = {'Monday': 0, 'Tuesday': 1, 'Wednesday': 2, 'Thursday': 3, 'Friday': 4, 'Saturday': 5, 'Sunday': 6}

    barbershop = models.ForeignKey(Barbershop, on_delete=models.DO_NOTHING)
    day = models.CharField(u'Jours', default='', max_length=10)
    day_id = models.IntegerField(u'Identifiant du Jour - Lundi 0, Dimanche 6', default=0)
    open_time = models.TimeField(verbose_name='Heure d\'ouverture', null=True, blank=True)
    closed_time = models.TimeField(verbose_name='Heure de fermeture', null=True, blank=True)
    is_closed = models.BooleanField(default=False, verbose_name='Fermé')

    def __unicode__(self):
        return self.day

    class Meta:
        verbose_name = u'Heure d\'ouverture'


class Cut(models.Model):
    name = models.CharField(u'Nom', default='', max_length=50)
    picture = models.ImageField(u'Photo de la coupe - 600 x 600', default='')

    def __unicode__(self):
        return self.name

    class Meta:
        verbose_name = 'Coupe'


class Barber(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE, null=True)
    name = models.CharField(u'Nom', default='', max_length=50)
    description = models.TextField(u'Description', default='', max_length=250)
    picture = models.ImageField(u'Photo - 650x720', default='')
    is_featured = models.BooleanField(u'Première Page', default=False)
    barbershop = models.ForeignKey(Barbershop, on_delete=models.DO_NOTHING)
    timezone = TimeZoneField(default='Europe/Zurich')
    calendar = models.ForeignKey(DBCollection, on_delete=models.CASCADE, null=True)
    online_appointment = models.BooleanField(default=False)

    def availabilities_for_service(self, service, start_day, stop_at_first_availability):
        start_day_or = datetime.strptime(start_day, "%Y-%m-%d")
        start_day = start_day_or
        end_day = start_day + timedelta(days=7)
        availability_found = False
        arr_results = []
        events = DBCollection.get_events(self.calendar, start_day, end_day)

        delta_day = timedelta(days=1)

        while start_day <= end_day:
            arr_availabilities = []
            barbershop_schedule = self.barbershop.get_schedule(Schedule.DAY_ID[start_day.strftime("%A")]).first()
            text_group = "%02d-%02d-%d" % (start_day.day, start_day.month, start_day.year)

            if not barbershop_schedule.is_closed:
                closing_hour = barbershop_schedule.closed_time
                closing_hour = datetime(start_day.year, start_day.month, start_day.day, closing_hour.hour,
                                        closing_hour.minute, closing_hour.second)
                opening_hour = barbershop_schedule.open_time
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

                            # if we only need to retrieve the first availability break
                            if stop_at_first_availability:
                                availability_found = True
                                break

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
        verbose_name = 'Barbier'


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


class Pricing(models.Model):
    name = models.CharField(u'Nom de la formule', default='', max_length=50)
    description = models.CharField(u'Description de la formule', default='', max_length=100)
    detail = models.CharField(u'Détail de la formule', default='', max_length=250)
    price = models.CharField(u'Prix', default='', max_length=10)
    column_id = models.IntegerField(u'Numéro de la colonne - Affichage', default=0)
    barbershop = models.ForeignKey(Barbershop, on_delete=models.DO_NOTHING)

    def __unicode__(self):
        return self.name

    class Meta:
        verbose_name = 'Formule'
