from django.urls import re_path

from agenda.views import schedule

urlpatterns = [
    re_path(
        r'^(?P<barber_id>[0-9]+)/(?P<service_id>[0-9]+)/(?P<start_date>[0-9]{4}-[0-9]+-[0-9]+)$',
        schedule.barber_schedule,
        name="barber-schedule"),
    re_path(
        r'^validate/(?P<slug>[0-9A-Za-z]{32})$',
        schedule.barber_schedule_validate,
        name="barber-schedule-validate"),
]
