import type { Variables } from '$lib/interfaces/variables';

const BASE_CMS_URL: string = import.meta.env.DEV
    ? import.meta.env.VITE_BASE_CMS_URL_DEV
    : import.meta.env.VITE_BASE_CMS_URL_PROD;

const BASE_CALENDAR_URL: string = import.meta.env.DEV
    ? import.meta.env.VITE_BASE_CALENDAR_URL_DEV
    : import.meta.env.VITE_BASE_CALENDAR_URL_PROD;

export const variables: Variables = { BASE_CMS_URL: BASE_CMS_URL, BASE_CALENDAR_URL: BASE_CALENDAR_URL };
