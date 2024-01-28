import {
    EASYAPPOINTMENTS_BASE_URL,
    EASYAPPOINTMENTS_PROVIDER_ID
} from "$lib/utils/constants";

const api_v1 = "index.php/api/v1";

export const getAppointmentsUrl = (servicedId: number, date: string): string => {
    const url = `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/availabilities?providerId=${EASYAPPOINTMENTS_PROVIDER_ID}&serviceId=${servicedId}&date=${date}`;
    return url;
}

export const getProvidersUrl = (providerId: string): string => {
    const url = `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/providers?providerId=${providerId}`;
    return url;
}

export const getServicesFromProviderUrl = (providerId: string): string => {
    const url = `${EASYAPPOINTMENTS_BASE_URL}/${api_v1}/services?providerId=${providerId}`;
    return url;
}
