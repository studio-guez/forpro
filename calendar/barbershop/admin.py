from django.contrib import admin
from django.forms.widgets import TextInput

from django_google_maps import widgets as map_widgets
from django_google_maps import fields as map_fields

from barbershop.models import Barbershop, City, Schedule, Country, Cut, Barber, Pricing, Slider
# Register your models here.


class BarbershopAdmin(admin.ModelAdmin):
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


class BarberAdmin(admin.ModelAdmin):
    list_display = ['name', 'get_barbershop', 'get_user']

    def get_barbershop(self, obj):
        return obj.barbershop

    def get_user(self, obj):
        return obj.user

    get_barbershop.admin_order_field = 'barbershop'  # Allows column order sorting
    get_barbershop.short_description = 'Barbershop'


class CityAdmin(admin.ModelAdmin):
    formfield_overrides = {
        map_fields.AddressField: {'widget': map_widgets.GoogleMapsAddressWidget},
        map_fields.GeoLocationField: {'widget': TextInput(attrs={'readonly': 'readonly'})},
    }


class ScheduleAdmin(admin.ModelAdmin):
    list_display = ['day', 'day_id', 'get_barbershop']

    def get_barbershop(self, obj):
        return obj.barbershop

    get_barbershop.admin_order_field = 'barbershop'  # Allows column order sorting
    get_barbershop.short_description = 'Barbershop'


class PricingAdmin(admin.ModelAdmin):
    list_display = ['name', 'get_barbershop']
    save_as = True

    def get_barbershop(self, obj):
        return obj.barbershop

    get_barbershop.admin_order_field = 'barbershop'  # Allows column order sorting
    get_barbershop.short_description = 'Barbershop'


Models = [Country, Cut, Slider]

admin.site.register(Barber, BarberAdmin)
admin.site.register(City, CityAdmin)
admin.site.register(Schedule, ScheduleAdmin)
admin.site.register(Barbershop, BarbershopAdmin)
admin.site.register(Pricing, PricingAdmin)
admin.site.register(Models)
