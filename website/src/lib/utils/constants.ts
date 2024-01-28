import type { Variables } from '$lib/interfaces/variables';

export const BASE_CMS_URL: string = import.meta.env.DEV
    ? import.meta.env.VITE_BASE_CMS_URL_DEV
    : import.meta.env.VITE_BASE_CMS_URL_PROD;

export const BASE_EASYAPPOINTMENTS_URL: string = import.meta.env.DEV
    ? import.meta.env.VITE_BASE_EASYAPPOINTMENTS_URL_DEV
    : import.meta.env.VITE_BASE_EASYAPPOINTMENTS_URL_PROD;

export const variables: Variables = {
    BASE_CMS_URL: BASE_CMS_URL,
    BASE_EASYAPPOINTMENTS_URL: BASE_EASYAPPOINTMENTS_URL
};
