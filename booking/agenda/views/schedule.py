from django.http import JsonResponse
from people.models import Person
from agenda.models import EventCategory
from django.shortcuts import render
from django.contrib import messages
from agenda.models import Event


def person_schedule(person_id: int, service_id: int, start_date) -> JsonResponse:
    person = Person.objects.get(id=person_id)
    service = EventCategory.objects.get(id=service_id)
    availabilities = person.availabilities_for_service(service, start_date, False)

    return JsonResponse(availabilities, safe=False)


def person_schedule_validate(request, slug: str) -> render:
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
