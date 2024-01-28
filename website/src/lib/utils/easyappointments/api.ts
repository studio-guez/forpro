import type {Slot, Service, Provider, Customer, Appointment} from '$lib/interfaces/variables';
import {
    getAppointmentsUrl,
    getAvailabilitiesUrl, getCustomersFromEmailUrl,
    getCustomersUrl,
    getServicesFromProviderUrl
} from "$lib/utils/easyappointments/urls";
import {fetchFromAPI, getHeaders} from "$lib/utils/shared";

export const getAvailableSlots = async (date: string, serviceId: number, providerId: number): Promise<Slot[]> => {
    const body = {date: date, providerId: providerId, serviceId: serviceId};

    const request = new Request(getAvailabilitiesUrl(providerId, serviceId, date), {
        headers: getHeaders(),
    });

    return fetchFromAPI<Slot[]>(request, 'Failed to fetch available slots');
}

export const getServicesFromProvider = async (providerId: string): Promise<Service[]> => {
    const request = new Request(getServicesFromProviderUrl(providerId), {
        headers: getHeaders(),
    });

    return fetchFromAPI<Service[]>(request, 'Failed to fetch services');
}

export const getCustomer = async (email: string): Promise<Customer> => {
    const request = new Request(`${getCustomersFromEmailUrl(email)}`, {
        headers: getHeaders(),
    });

    return fetchFromAPI<Customer>(request, 'Failed to fetch customer');
}

export const createCustomer = async (email: string, firstname: string, lastname: string, phone: string): Promise<Customer> => {
    const body = {
        email: email,
        firstName: firstname,
        lastName: lastname,
        phone: phone,
        city: "",
        zip: "",
        timezone: "",
        language: "",
        notes: ""
    };

    const request = new Request(getCustomersUrl(), {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(body)
    });

    return fetchFromAPI<Customer>(request, 'Failed to create customer');
}

export const createAppointment = async (start: string, end: string, serviceId: string, providerId: string, customerId: string, notes: string): Promise<Appointment> => {
    const body = {
        start: start,
        end: end,
        location: '',
        notes: notes,
        serviceId: serviceId,
        providerId: providerId,
        customerId: customerId,
    };

    console.log(body);

    const request = new Request(getAppointmentsUrl(), {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify(body)
    });

    return fetchFromAPI<Appointment>(request, 'Failed to create appointment');
}
