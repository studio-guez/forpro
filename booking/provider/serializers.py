from rest_framework import serializers
from provider.models import Person, Event, ProviderService

class ProviderServiceSerializer(serializers.ModelSerializer):
    class Meta:
        model = ProviderService
        fields = '__all__'

class PersonSerializer(serializers.ModelSerializer):
    class Meta:
        model = Person
        fields = '__all__'

class EventSerializer(serializers.ModelSerializer):
    class Meta:
        model = Event
        fields = '__all__'
