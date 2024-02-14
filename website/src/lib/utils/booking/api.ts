import type {Slot, Service, Appointment} from '$lib/interfaces/variables';
import {
    getAppointmentsUrl,
    getServices,
    getSlots
} from "$lib/utils/booking/urls";

import {fetchFromAPI, getHeaders} from "$lib/utils/shared";

export const getAvailableSlots = async (date: string, serviceId: string, calendarId: string): Promise<Slot[]> => {
    const body = {date: date, providerId: calendarId, serviceId: serviceId};

    const request = new Request(getSlots(calendarId, serviceId, date), {
        method: 'GET',
        headers: getHeaders(),
    });

    return fetchFromAPI<Slot[]>(request, 'Failed to fetch available slots');
}

export const getServicesFromCalendarId = async (calendarId: string): Promise<Service[]> => {
    const request = new Request(getServices(calendarId), {
        headers: getHeaders(),
    });

    return fetchFromAPI<Service[]>(request, 'Failed to fetch services');
}

export const createAppointment = async (start: string, end: string, serviceId: string, providerId: string, notes: string, firstName: string, lastName: string, phone: string, email: string):
    Promise<Appointment> => {
    const body = {
        start: start,
        end: end,
        location: '',
        notes: notes,
        serviceId: serviceId,
        providerId: providerId,
        firstName: firstName,
        lastName: lastName,
        phone: phone,
        email: email,
    };

    console.log(body);

    const request = new Request(getAppointmentsUrl(), {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(body)
    });

    return fetchFromAPI<Appointment>(request, 'Failed to create appointment');
}
