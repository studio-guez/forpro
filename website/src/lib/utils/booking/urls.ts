import {BOOKING_BASE_URL} from "$lib/utils/constants";

export const getAvailabilitiesUrl = (providerId: string, servicedId: string, date: string): string => {
    return `${BOOKING_BASE_URL}/agenda/day/${providerId}/${servicedId}/${date}`;
}

export const getServicesFromProviderUrl = (providerId: string): string => {
    return `${BOOKING_BASE_URL}/provider/${providerId}`;
}

export const getAppointmentsUrl = (): string => {
    return `${BOOKING_BASE_URL}/api/appointment/`;
}
