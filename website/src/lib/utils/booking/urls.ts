import {variables} from "$lib/utils/constants";

export const getSlotsUrl = (calendarId: string, servicedId: string, date: string): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/get-slots/${calendarId}/${servicedId}/${date}`;
}

export const getCalendarOptionsUrl = (calendarId: string): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/${calendarId}/options/`;
}

export const getServicesUrl = (calendarId: string): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/${calendarId}/services/`;
}

export const getSchedulesUrl = (calendarId: string): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/${calendarId}/schedules/`;
}

export const addEventUrl = (): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/add-slot`;
}

export const getAppointmentsUrl = (eventId: string): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/${eventId}/confirm/`;
}
