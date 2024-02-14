import type {Actions, PageServerLoad} from './$types';
import {variables} from "$lib/utils/constants";
import type {BookingCMSResponse} from "$lib/interfaces/variables";
import {createAppointment, getAvailableSlots, getServicesFromCalendarId,} from "$lib/utils/booking/api";
import dayjs from "dayjs";

export const prerender = false;

export const load: PageServerLoad = async ({params, fetch}) => {
    const cmsBookingUrl = `${variables.CMS_BASE_URL}/booking`
    const calendarId = params.calendarId;

    let availabilities = [];

    const res = await fetch(cmsBookingUrl);
    const content: BookingCMSResponse = await res.json();

    const servicesKirby = await getServicesFromCalendarId(calendarId);
    const services = Object.values(servicesKirby);

    const today = dayjs().format('YYYY-MM-DD');

    if (services !== undefined) {
        availabilities = await getAvailableSlots(today, services[0].id, calendarId);
    }

    return {content, services, calendarId, availabilities};
};

export const actions = {
    default: async ({request}) => {

        const data = await request.formData();
        const email = data.get('email');
        const firstname = data.get('firstname');
        const lastname = data.get('lastname');
        const phone = data.get('phone');
        const calendarId = data.get('calendarId');
        const serviceId = data.get('serviceId');
        const date = data.get('date');
        const slot = data.get('slot');

        const infos = {
            firstname,
            lastname,
            phone,
            email,
        };

        const appointment = await createAppointment(
            date,
            slot,
            serviceId,
            calendarId,
            infos
        );

        return {appointment};
    },
} satisfies Actions;

const createDates = ({date, slot, duration}) => {
    const start = dayjs(`${date}T${slot}:00`);
    console.log(start);

    // Create end date from start plus duration
    const end = start.add(duration, 'minute');

    // Format the date to be compatible with easyappointments
    const formattedStart = start.format('YYYY-MM-DD HH:mm:ss');
    const formattedEnd = end.format('YYYY-MM-DD HH:mm:ss');

    return {start: formattedStart, end: formattedEnd};
};
