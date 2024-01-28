import {EASYAPPOINTMENTS_BASE_URL} from "$lib/utils/constants";

const api_v1 = "index.php/api/v1";

export const getAvailabilitiesUrl = (providerId: string, servicedId: string, date: string): string => {
    return `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/availabilities?providerId=${providerId}&serviceId=${servicedId}&date=${date}`;
}

export const getServicesFromProviderUrl = (providerId: string): string => {
    return `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/services?providerId=${providerId}`;
}

export const getAppointmentsUrl = (): string => {
    return `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/appointments`;
}

export const getCustomersFromEmailUrl = (email: string): string => {
    return `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/customers?email=${email}`;
}

export const getCustomersUrl = (): string => {
    return `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/customers`;
}
