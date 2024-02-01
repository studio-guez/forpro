# -*- coding: utf-8 -*-
import pytz

from datetime import datetime, timedelta

from django.core.mail import EmailMultiAlternatives
from django.template.loader import get_template
from django.contrib import messages
from django import forms

from provider.models import Person, Event
from provider.models import ProviderService
from djradicale.models import DBCollection


class AppointmentForm(forms.Form):
    services = ProviderService.objects.all()
    persons = Person.objects.all()

    srv_cho = forms.ModelChoiceField(queryset=services, empty_label="Sélectionnez un Service", widget=forms.Select(
        attrs={'class': 'selectpicker form-control',
               'id': 'service-select', 'data-style': 'btn-white'}))
    brb_cho = forms.ModelChoiceField(queryset=persons, empty_label="Sélectionnez un Barbier", widget=forms.Select(
        attrs={'class': 'selectpicker form-control',
               'id': 'barber-select', 'data-style': 'btn-white'}))
    cus_name = forms.CharField(widget=forms.TextInput(attrs={'placeholder': 'Nom, Prénom', 'class': 'form-control'}))
    cus_phone = forms.CharField(widget=forms.TextInput(attrs={'placeholder': 'Tél: +41791232131', 'class': 'form-control', 'autocomplete': 'tel'}))
    cus_email = forms.EmailField(widget=forms.TextInput(attrs={'placeholder': 'E-mail*', 'class': 'form-control', 'autocomplete': 'email'}))

    def save_appointment(self, request):
        if self.is_valid():
            # GET FIELDS
            service = self.cleaned_data['srv_cho']
            person = self.cleaned_data['brb_cho']
            collection = person.calendar
            customer_email = self.data['cus_email']
            customer_name = self.cleaned_data['cus_name']
            customer_phone = self.cleaned_data['cus_phone']
            description = customer_phone + '\n' + service.name
            event_date = datetime.strptime(request.POST.get('availability'), "%d-%m-%YT%H:%M")
            start_time = event_date.time()
            event_end_date = event_date + timedelta(hours=service.duration.hour, minutes=service.duration.minute)
            end_time = event_end_date.time()

            timezone = person.provider.timezone
            event_date.replace(tzinfo=timezone)

            author = customer_email
            event = Event.create(event_date, start_time, end_time,
                                 author, customer_name, description, timezone, collection)

            event.save()

            # Email
            ctx = {
                'person': person,
                'event': event,
                'service': service,
            }

            html = get_template('appointment_confirm.html')
            html_content = html.render(ctx)

            msg = EmailMultiAlternatives("Confirmez votre rendez-vous !", html_content, "no-reply@the-sharpness.com",
                                         [customer_email])
            msg.attach_alternative(html_content, "text/html")

            msg.send()

            # USER RETURN
            storage = messages.get_messages(request)
            storage.used = True
            messages.success(request, "Votre rendez-vous a été retenu, "
                                      "merci de le valider \n en cliquant sur le lien reçu par email !")

        return True


class AppointmentBarberForm(forms.Form):
    services = ProviderService.objects.all()
    persons = Person.objects.all()

    srv_cho = forms.ModelChoiceField(queryset=services, empty_label="Sélectionnez un Service", widget=forms.Select(
        attrs={'class': 'selectpicker form-control',
               'id': 'service-select', 'data-style': 'btn-white'}))
    brb_cho = forms.ModelChoiceField(queryset=persons, empty_label="Sélectionnez un Barbier", widget=forms.Select(
        attrs={'class': 'selectpicker form-control',
               'id': 'barber-select', 'data-style': 'btn-white'}))
    cus_name = forms.CharField(widget=forms.TextInput(attrs={'placeholder': 'Nom, Prénom', 'class': 'form-control'}))
    cus_phone = forms.CharField(
        widget=forms.TextInput(attrs={'placeholder': 'Tél: +41791232131', 'class': 'form-control', 'autocomplete': 'tel'}))

    def save_appointment(self, request):
        if self.is_valid():
            # GET FIELDS
            service = self.cleaned_data['srv_cho']
            barber = self.cleaned_data['brb_cho']
            customer_name = self.cleaned_data['cus_name']
            customer_phone = self.cleaned_data['cus_phone']
            collection = barber.calendar
            description = customer_phone + '\n' + service.name
            event_date = datetime.strptime(request.POST.get('availability'), "%d-%m-%YT%H:%M")
            start_time = event_date.time()
            event_end_date = event_date + timedelta(hours=service.duration.hour, minutes=service.duration.minute)
            end_time = event_end_date.time()

            timezone = barber.barbershop.timezone
            event_date.replace(tzinfo=timezone)

            event = Event.create(event_date, start_time, end_time, 'barbier', customer_name, description, timezone, collection)

            # USER RETURN
            storage = messages.get_messages(request)
            storage.used = True
            messages.success(request, "Rendez-Vous inscrit !")

            return event.save()
