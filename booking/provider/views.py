from django.http import JsonResponse

from provider.models import Provider, ProviderService

from rest_framework import viewsets
from provider.models import ProviderService, Person, Event
from .serializers import ProviderServiceSerializer, PersonSerializer, EventSerializer

class ProviderServiceViewSet(viewsets.ModelViewSet):
    queryset = ProviderService.objects.all()
    serializer_class = ProviderServiceSerializer

class PersonViewSet(viewsets.ModelViewSet):
    queryset = Person.objects.all()
    serializer_class = PersonSerializer

class EventViewSet(viewsets.ModelViewSet):
    queryset = Event.objects.all()
    serializer_class = EventSerializer

def get_services(request, provider_id):
    provider = Provider.objects.get(id=provider_id)
    services = list(ProviderService.objects.filter(provider=provider).values('id', 'name', 'duration'))

    return JsonResponse(services, safe=False)
