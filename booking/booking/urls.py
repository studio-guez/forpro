from django.conf import settings
from django.conf.urls.static import static
from django.contrib import admin
from django.urls import re_path, include, path

from djradicale.views import WellKnownView

urlpatterns = [
    path('admin/', admin.site.urls),
    path('agenda/', include('agenda.urls')),
    path('api-auth/', include('rest_framework.urls', namespace='rest_framework')),
    re_path(r'^' + settings.DJRADICALE_CONFIG['server']['base_prefix'].lstrip('/'),
            include(('djradicale.urls', 'djradicale'))),
    re_path(r'^\.well-known/(?P<type>(caldav|carddav))$', WellKnownView.as_view(),
            name='djradicale_well-known'),
]

# Branding Admin
admin.site.site_title = "Calendrier"
admin.site.site_header = "Calendrier - Administration"
