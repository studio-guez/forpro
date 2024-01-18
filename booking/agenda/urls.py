from django.urls import path

from agenda.views import schedule

urlpatterns = [
    path('<int:person_id>/<int:service_id>/<start_date>',
         schedule.person_schedule,
         name="person-schedule"),
    path('validate/<slug:slug>',
         schedule.person_schedule_validate,
         name="person-schedule-validate"),
]
