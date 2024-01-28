import {EASYAPPOINTMENTS_BASE_URL} from "$lib/utils/constants";

const api_v1 = "index.php/api/v1";

export const getAppointmentsUrl = (providerId: number, servicedId: number, date: string): string => {
    return `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/availabilities?providerId=${providerId}&serviceId=${servicedId}&date=${date}`;
}

export const getProvidersUrl = (providerId: string): string => {
    return `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/providers?providerId=${providerId}`;
}

export const getServicesFromProviderUrl = (providerId: string): string => {
    return `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/services?providerId=${providerId}`;
}
