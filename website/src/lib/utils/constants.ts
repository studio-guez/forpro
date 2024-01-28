import type { Variables } from '$lib/interfaces/variables';

export const CMS_BASE_URL: string = import.meta.env.DEV
    ? import.meta.env.VITE_CMS_BASE_URL_DEV
    : import.meta.env.VITE_CMS_BASE_URL_PROD;

export const EASYAPPOINTMENTS_BASE_URL: string = import.meta.env.DEV
    ? import.meta.env.VITE_EASYAPPOINTMENTS_BASE_URL_DEV
    : import.meta.env.VITE_EASYAPPOINTMENTS_BASE_URL_PROD;

export const EASYAPPOINTMENTS_API_TOKEN: string = import.meta.env.VITE_EASYAPPOINTMENTS_API_TOKEN;

export const variables: Variables = {
    CMS_BASE_URL: CMS_BASE_URL,
    EASYAPPOINTMENTS_BASE_URL: EASYAPPOINTMENTS_BASE_URL,
    EASYAPPOINTMENTS_API_TOKEN: EASYAPPOINTMENTS_API_TOKEN,
};
