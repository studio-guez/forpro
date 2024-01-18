from django.contrib import admin
from people.models import Location, Schedule, Person


class LocationAdmin(admin.ModelAdmin):
    fieldsets = (
        ('Informations', {
            'fields': ('name', 'description')
        }),
        ('Geo-Localisation', {
            'fields': ('address', 'code', 'city')
        }),
        ('Contact', {
            'fields': ('email', 'phone')
        }),
        ('Images', {
            'fields': ('banner', 'picture_appointment')
        }),
        ('SEO', {
            'fields': ('meta_title', 'meta_description', 'meta_tag')
        })
    )


class PersonAdmin(admin.ModelAdmin):
    list_display = ['name', 'get_location', 'get_user']

    def get_location(self, obj):
        return obj.location

    def get_user(self, obj):
        return obj.user

    get_location.admin_order_field = 'location'  # Allows column order sorting
    get_location.short_description = 'Location'


class ScheduleAdmin(admin.ModelAdmin):
    list_display = ['day', 'day_id', 'get_location']

    def get_location(self, obj):
        return obj.location

    get_location.admin_order_field = 'location'  # Allows column order sorting
    get_location.short_description = 'Location'


admin.site.register(Person, PersonAdmin)
admin.site.register(Schedule, ScheduleAdmin)
admin.site.register(Location, LocationAdmin)
