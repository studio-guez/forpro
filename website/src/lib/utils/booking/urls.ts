import {variables} from "$lib/utils/constants";

export const getSlots = (calendarId: string, servicedId: string, date: string): string => {
    const url = `${variables.CMS_BASE_URL}/kirby-calendars/get-slots/${calendarId}/${servicedId}/${date}`;
    console.log(url);
    return url;
}

export const getServices = (calendarId: string): string => {
    const url = `${variables.CMS_BASE_URL}/kirby-calendars/${calendarId}/services/`;
    console.log(url);
    return url;
}

export const getAppointmentsUrl = (): string => {
    return `${variables.CMS_BASE_URL}/kirby-calendars/add-slot`;
}
