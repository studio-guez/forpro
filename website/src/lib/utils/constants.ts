import type { Variables } from '$lib/interfaces/variables';

export const CMS_BASE_URL: string = import.meta.env.DEV
    ? import.meta.env.VITE_CMS_BASE_URL_DEV
    : import.meta.env.VITE_CMS_BASE_URL_PROD;

export const BOOKING_BASE_URL: string = import.meta.env.DEV
    ? import.meta.env.VITE_BOOKING_BASE_URL_DEV
    : import.meta.env.VITE_BOOKING_BASE_URL_PROD;

export const BOOKING_API_TOKEN: string = import.meta.env.VITE_BOOKING_API_TOKEN;

export const variables: Variables = {
    CMS_BASE_URL: CMS_BASE_URL,
    BOOKING_BASE_URL: BOOKING_BASE_URL,
    BOOKING_API_TOKEN: BOOKING_API_TOKEN,
};
