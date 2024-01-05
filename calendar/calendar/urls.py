from django.conf import settings
from django.conf.urls.static import static
from django.contrib import admin
from django.urls import re_path, include, path

from djradicale.views import WellKnownView

from sharpness import views

urlpatterns = [
    path('', views.homepage, name='homepage'),
    path('admin/', admin.site.urls),
    path('agenda/', include('agenda.urls')),
    path('api-auth/', include('rest_framework.urls', namespace='rest_framework')),
    re_path(r'^' + settings.DJRADICALE_CONFIG['server']['base_prefix'].lstrip('/'),
            include(('djradicale.urls', 'djradicale'))),
    re_path(r'^\.well-known/(?P<type>(caldav|carddav))$', WellKnownView.as_view(),
            name='djradicale_well-known'),
]

# DJANGO DEBUG TOOLBAR
if settings.DEBUG:
    import debug_toolbar

    urlpatterns += static(settings.STATIC_URL, document_root=settings.STATIC_ROOT)
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
    urlpatterns += [
        path('__debug__/', include(debug_toolbar.urls)),
    ]

# Branding Admin
admin.site.site_title = "Calendrier"
admin.site.site_header = "Calendrier - Administration"
