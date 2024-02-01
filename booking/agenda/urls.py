from django.urls import re_path

from agenda.views import schedule

urlpatterns = [
    re_path(
        r'^day/(?P<person_id>[0-9]+)/(?P<service_id>[0-9]+)/(?P<start_date>[0-9]{4}-[0-9]+-[0-9]+)$',
        schedule.person_schedule_day,
        name="person-schedule"
    ),
    re_path(
        r'^month/(?P<person_id>[0-9]+)/(?P<service_id>[0-9]+)/(?P<start_date>[0-9]{4}-[0-9]+-[0-9]+)$',
        schedule.person_schedule_month,
        name="person-schedule-month"
    ),
    re_path(
        r'^validate/(?P<slug>[0-9A-Za-z]{32})$',
        schedule.person_schedule_validate,
        name="person-schedule-validate"
    ),
]
