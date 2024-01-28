import type {Slot, Service, Provider} from '$lib/interfaces/variables';
import {getAppointmentsUrl, getProvidersUrl, getServicesFromProviderUrl} from "$lib/utils/easyappointments/urls";
import {fetchFromAPI, getHeaders} from "$lib/utils/shared";

export const fetchAvailableSlots = async (date: string, serviceId: number, providerId: number): Promise<Slot[]> => {
    const body = {date: date, providerId: providerId, serviceId: serviceId};

    const request = new Request(getAppointmentsUrl(serviceId, date), {
        headers: getHeaders(),
    });

    return fetchFromAPI<Slot[]>(request, 'Failed to fetch available slots');
}

export const fetchServicesFromProvider = async (providerId: string): Promise<Service[]> => {
    const request = new Request(getServicesFromProviderUrl(providerId), {
        headers: getHeaders(),
    });

    return fetchFromAPI<Service[]>(request, 'Failed to fetch services');
}
