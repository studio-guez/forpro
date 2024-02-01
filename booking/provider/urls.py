from django.urls import path, re_path

from provider.views import get_services

urlpatterns = [
    re_path(
        r'^(?P<provider_id>[0-9]+)',
        get_services,
        name="provider-services"
    ),
]
