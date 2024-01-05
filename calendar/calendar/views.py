import datetime

from django.conf import settings
from django.shortcuts import render

from appointment.forms import AppointmentForm
from barbershop.models import Barbershop, Schedule, City, Barber, Country, Cut, Slider
from contact.models import ContactUtils


def homepage(request):
    barbershop = Barbershop.objects.all().first()
    google_map_api = settings.GOOGLE_MAPS_API_KEY
    today_id = datetime.datetime.today().weekday()
    time = datetime.datetime.now().time()
    is_homepage = True
    form = AppointmentForm()
    sliders = Slider.objects.all().order_by('order')

    if request.method == 'POST':
        form = AppointmentForm(request.POST)
        if form.save_appointment(request):
            render(request, 'appointment_validation.html')

    form.fields["brb_cho"].queryset = Barber.objects.filter(online_appointment=True)

    context = {
        'form': form,
        'is_homepage': is_homepage,
        'google_map_api': google_map_api,
        'today_id': today_id,
        'time': time,
        'sliders': sliders,
        'barbershop': barbershop
    }

    navbar_ctxt = get_navbar_context()
    page_context = {**navbar_ctxt, **context}

    return render(request, 'index.html', page_context)


# Contact Page
def contact(request):
    contact_utils = ContactUtils.objects.first()
    barbershop_list = Barbershop.objects.all()
    google_map_api = settings.GOOGLE_MAPS_API_KEY

    navbar_context = get_navbar_context()

    view_context = {
        'contact_utils': contact_utils,
        'barbershop_list': barbershop_list,
        'google_map_api': google_map_api,
    }

    page_context = {**navbar_context, **view_context}

    return render(request, 'contact.html', page_context)


# Create a function called "chunks" with two arguments, l and n:
def chunks(l, n):
    # For item i in a range that is a length of l,
    for i in range(0, len(l), n):
        # Create an index range for l of n items:
        yield l[i:i + n]


def page_not_found(request, exception):
    google_map_api = settings.GOOGLE_MAPS_API_KEY
    today_id = datetime.datetime.today().weekday()
    time = datetime.datetime.now().time()
    is_homepage = True

    context = {
        'is_homepage': is_homepage,
        'google_map_api': google_map_api,
        'today_id': today_id,
        'time': time,
    }

    navbar_ctxt = get_navbar_context()
    page_context = {**navbar_ctxt, **context}

    return render(request, '404.html', page_context)


def get_navbar_context():
    barbershops = Barbershop.objects.all()
    barbers = Barber.objects.filter(is_featured=True)
    cuts = Cut.objects.all()
    schedules = Schedule.objects.all().prefetch_related('barbershop')
    countries = Country.objects.all()
    cities = City.objects.all()

    context = {
        'barbershops': barbershops,
        'cuts': cuts,
        'barbers': barbers,
        'schedules': schedules,
        'cities': cities,
        'countries': countries,
    }

    return context
