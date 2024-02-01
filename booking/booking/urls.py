from django.conf import settings
from django.conf.urls.static import static
from django.contrib import admin
from django.urls import re_path, include, path
from provider.views import ProviderServiceViewSet, PersonViewSet, EventViewSet
from appointment.views import AppointmentAPI

from djradicale.views import WellKnownView

from rest_framework.routers import DefaultRouter

router = DefaultRouter()
router.register(r'providerservices', ProviderServiceViewSet)
router.register(r'persons', PersonViewSet)
router.register(r'events', EventViewSet)

urlpatterns = [
    path('admin/', admin.site.urls),
    path('agenda/', include('agenda.urls')),
    path('provider/', include('provider.urls')),
    path('api/', include(router.urls)),
    path('api/appointment/', AppointmentAPI.as_view(), name='appointment-api'),
    path('api-auth/', include('rest_framework.urls', namespace='rest_framework')),
    re_path(r'^' + settings.DJRADICALE_CONFIG['server']['base_prefix'].lstrip('/'),
            include(('djradicale.urls', 'djradicale'))),
    re_path(r'^\.well-known/(?P<type>(caldav|carddav))$', WellKnownView.as_view(),
            name='djradicale_well-known'),
] + router.urls

# DJANGO DEBUG TOOLBAR
if settings.DEBUG:
    import debug_toolbar
    urlpatterns += static(settings.STATIC_URL, document_root=settings.STATIC_ROOT)
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
    urlpatterns += [
        path('__debug__/', include(debug_toolbar.urls)),
    ]

# Branding Admin
admin.site.site_title = "Booking"
admin.site.site_header = "Booking - Administration"
