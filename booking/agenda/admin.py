from django.contrib import admin
from django.utils.translation import ugettext as _

from agenda.models import Event, EventCategory


class EventAdmin(admin.ModelAdmin):
    list_display = ('title', 'author', 'event_date', 'start_time', 'publish', 'collection', 'description')
    list_display_links = ('title', )
    list_filter = ('event_date', 'publish', 'collection')

    date_hierarchy = 'event_date'
    
    prepopulated_fields = {"slug": ("title",)}
    
    search_fields = ('title', 'author__username', 'author__first_name', 'author__last_name', 'collection')

    fieldsets = ((None, {'fields': ['title', 'slug', 'event_date', 'start_time', 'end_time', 'description', 'collection',]}),
                  (_('Advanced options'), {'classes' : ('collapse',),
                                           'fields'  : ('publish_date', 'publish', 'author', 'allow_comments')}))


admin.site.register(Event, EventAdmin)
admin.site.register(EventCategory)
