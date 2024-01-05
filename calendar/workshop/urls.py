from django.urls import path

from workshop import views

urlpatterns = [
    path('', views.workshop_list, name="workshop-list"),
    path('<int:id>/<str:title>', views.workshop_detail, name="workshop-detail")
]