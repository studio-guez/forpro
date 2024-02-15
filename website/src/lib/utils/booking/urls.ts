import {variables} from "$lib/utils/constants";

export const getSlots = (calendarId: string, servicedId: string, date: string): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/get-slots/${calendarId}/${servicedId}/${date}`;
}

export const getServices = (calendarId: string): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/${calendarId}/services/`;
}

export const getSchedules = (calendarId: string): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/${calendarId}/schedules/`;
}

export const getAppointmentsUrl = (): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/add-slot`;
}
