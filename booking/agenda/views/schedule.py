from django.http import JsonResponse
from django.shortcuts import render
from django.contrib import messages

from agenda.models import Event
from provider.models import Person, ProviderService


def person_schedule(request, person_id, service_id, start_date):
    person = Person.objects.get(id=person_id)
    service = ProviderService.objects.get(id=service_id)
    availabilities = person.availabilities_for_service(service, start_date)

    return JsonResponse(availabilities, safe=False)


def person_schedule_day(request, person_id, service_id, start_date):
    person = Person.objects.get(id=person_id)
    service = ProviderService.objects.get(id=service_id)
    availabilities = person.availabilities_for_service_one_day(service, start_date)

    return JsonResponse(availabilities, safe=False)


def person_schedule_month(request, person_id, service_id, start_date):
    person = Person.objects.get(id=person_id)
    service = ProviderService.objects.get(id=service_id)
    availabilities = person.availabilities_for_service(service, start_date, month=True, day=False)

    return JsonResponse(availabilities, safe=False)


def person_schedule_validate(request, slug):
    event = Event.objects.get(slug=slug)

    if event:
        if event.publish:
            messages.warning(request, 'Ce rendez-vous a déjà été confirmé !')
        else:
            event.publish = True
            event.save()
            messages.success(request, 'Merci d\'avoir confirmé votre rendez-vous !')
    else:
        messages.error(request, 'Ce rendez-vous est introuvable... \n Merci d\'appeler le shop le plus proche')

    return render(request, 'appointment_validation.html')
