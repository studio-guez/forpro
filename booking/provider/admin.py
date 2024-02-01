from django.contrib import admin
from django.forms.widgets import TextInput

from django_google_maps import widgets as map_widgets
from django_google_maps import fields as map_fields

from .models import Provider, Person, ProviderSchedule, ProviderService


class ProviderAdmin(admin.ModelAdmin):
    list_display = ['name', 'phone', 'email', 'address', 'city', 'country', 'timezone', 'slug']


class ProviderServiceAdmin(admin.ModelAdmin):
    list_display = ['name', 'duration', 'get_provider']

    def get_provider(self, obj):
        return obj.provider

    get_provider.admin_order_field = 'provider'
    get_provider.short_description = 'Provider'


class PersonAdmin(admin.ModelAdmin):
    list_display = ['name', 'get_provider', 'get_user']

    def get_provider(self, obj):
        return obj.provider

    def get_user(self, obj):
        return obj.user

    get_provider.admin_order_field = 'provider'  # Allows column order sorting
    get_provider.short_description = 'Provider'


class ProviderScheduleAdmin(admin.ModelAdmin):
    list_display = ['day', 'day_id', 'get_provider', 'open_time', 'closed_time', 'is_closed']

    def get_provider(self, obj):
        return obj.provider

    get_provider.admin_order_field = 'provider'  # Allows column order sorting
    get_provider.short_description = 'provider'


admin.site.register(Person, PersonAdmin)
admin.site.register(Provider, ProviderAdmin)
admin.site.register(ProviderService, ProviderServiceAdmin)
admin.site.register(ProviderSchedule, ProviderScheduleAdmin)
