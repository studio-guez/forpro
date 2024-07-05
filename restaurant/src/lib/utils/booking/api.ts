import type {Slot, Service, Appointment, Options} from '$lib/interfaces/variables';
import {
    addEventUrl,
    getAppointmentsUrl,
    getCalendarOptionsUrl,
    getSchedulesUrl,
    getServicesUrl,
    getSlotsUrl
} from "$lib/utils/booking/urls";

import {fetchFromAPI, getHeaders} from "$lib/utils/shared";

export const getAvailableSlots = async (date: string, serviceId: string, calendarId: string): Promise<Slot[]> => {
    const body = {date: date, providerId: calendarId, serviceId: serviceId};

    const request = new Request(getSlotsUrl(calendarId, serviceId, date), {
        method: 'GET',
        headers: getHeaders(),
    });

    return fetchFromAPI<Slot[]>(request, 'Failed to fetch available slots');
}

export const getSchedulesFromCalendarId = async (calendarId: string): Promise<any> => {
    const request = new Request(getSchedulesUrl(calendarId), {
        headers: getHeaders(),
    });

    return fetchFromAPI<any>(request, 'Failed to fetch schedules');
}

export const getCalendarOptions = async (calendarId: string): Promise<Options> => {
    const request = new Request(getCalendarOptionsUrl(calendarId), {
        headers: getHeaders(),
    });

    return fetchFromAPI<any>(request, 'Failed to fetch options');
}

export const getServicesFromCalendarId = async (calendarId: string): Promise<Service[]> => {
    const request = new Request(getServicesUrl(calendarId), {
        headers: getHeaders(),
    });

    return fetchFromAPI<Service[]>(request, 'Failed to fetch services');
}

export const confirmAppointment = async (eventId: string): Promise<Appointment> => {
    const request = new Request(getAppointmentsUrl(eventId), {
        method: 'GET',
        headers: getHeaders(),
    });

    return fetchFromAPI<Appointment>(request, 'Failed to confirm appointment');
}

export const createAppointment = async (date: string,
                                        slot: string,
                                        subject: string,
                                        serviceId: string,
                                        calendarId: string,
                                        infos: []):
    Promise<Appointment> => {

    const body = {
        date: date,
        slot: slot,
        subject: subject,
        serviceId: serviceId,
        calendarId: calendarId,
        infos: infos
    };

    const request = new Request(addEventUrl(), {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(body)
    });

    return fetchFromAPI<Appointment>(request, 'Failed to create appointment');
}
