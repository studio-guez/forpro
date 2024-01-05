from datetime import datetime

from django.http import HttpResponseRedirect
from django.shortcuts import render

from agenda.models import EventCategory
from appointment.forms import AppointmentForm
from barbershop.models import Barbershop, Barber, Cut, City, Country, Schedule, Pricing


def barbershop_country(request, country):
    barbershop_list = Barbershop.objects.filter(location__country__name__iexact=country)
    schedule_list = Schedule.objects.all()
    today_id = datetime.today().weekday()

    context = {
        'today_id': today_id,
        'schedule_list': schedule_list,
        'barbershop_list': barbershop_list
    }

    navbar_ctxt = get_navbar_context()
    page_context = {**navbar_ctxt, **context}

    return render(request, 'barbershop.html', page_context)


def barbershop_city(request, id):
    barbershop = Barbershop.objects.get(location__city__iexact=id)
    barber_list = Barber.objects.filter(barbershop_id=barbershop.id)
    pricing_list = Pricing.objects.filter(barbershop=barbershop.id)

    today_id = datetime.today().weekday()
    time = datetime.now().time()
    form = AppointmentForm()

    context = {
        'form': form,
        'time': time,
        'today_id': today_id,
        'barbershop_list': barber_list,
        'pricing_list': pricing_list,
        'barbershop': barbershop,
        'barber_list': barber_list,
    }

    navbar_ctxt = get_navbar_context()
    page_context = {**navbar_ctxt, **context}

    return render(request, 'barbershop.html', page_context)


def barbershop_name(request, country, city, barbershop, id):
    barbershop = Barbershop.objects.get(pk=id)

    barbers = Barber.objects.filter(barbershop=barbershop)
    schedule = Schedule.objects.filter(barbershop=barbershop)
    pricings = Pricing.objects.filter(barbershop=barbershop)

    today_id = datetime.today().weekday()
    time = datetime.now().time()
    form = AppointmentForm()
    services = EventCategory.objects.all()

    form.fields["brb_cho"].queryset = barbers.filter(online_appointment=True)

    if request.method == 'POST':
        form = AppointmentForm(request.POST)
        if form.save_appointment(request):
            render(request, 'appointment_validation.html')

    context = {
        'form': form,
        'time': time,
        'today_id': today_id,
        'barbershop': barbershop,
    }

    navbar_ctxt = get_navbar_context()
    page_context = {**navbar_ctxt, **context}

    return render(request, 'barbershop.html', page_context)


def get_navbar_context():
    barbershops = Barbershop.objects.all()
    cities = City.objects.all()
    schedules = Schedule.objects.all()
    countries = Country.objects.all()
    cuts = Cut.objects.all
    prices = Pricing.objects.all

    context = {
        'cuts': cuts,
        'schedules': schedules,
        'cities': cities,
        'countries': countries,
        'barbershops': barbershops,
        'prices': prices
    }

    return context


def barbershop_name_old(request, country, city, barbershop, id):
    url = '/barbershop/' + str(id) + '/' + country + '/' + city + '/' + barbershop
    return HttpResponseRedirect(url)
