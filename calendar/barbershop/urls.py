from django.urls import path

from barbershop import views

urlpatterns = [
    path('<int:id>/<str:country>/<str:city>/<str:barbershop>', views.barbershop_name, name="barbershop-name"),
    path('<int:id>/<str:country>/<slug:city>', views.barbershop_city, name="barbershop-city"),
    path('<int:id>/<slug:country>', views.barbershop_country, name="barbershop-country"),
    path('<str:country>/<str:city>/<str:barbershop>/<int:id>', views.barbershop_name_old, name="barbershop-name-old"),
]
