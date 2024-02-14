import type {Actions, PageServerLoad} from './$types';
import {variables} from "$lib/utils/constants";
import type {BookingCMSResponse} from "$lib/interfaces/variables";
import {
    createAppointment,
    getAvailableSlots,
    getServicesFromCalendarId,
} from "$lib/utils/booking/api";
import dayjs from "dayjs";

export const prerender = false;

export const load: PageServerLoad = async ({ params, fetch }) => {
    const cmsBookingUrl = `${variables.CMS_BASE_URL}/booking`
    const calendarId = params.calendarId;

    let availabilities = [];

    const res = await fetch(cmsBookingUrl);
    const content: BookingCMSResponse = await res.json();

    const servicesKirby = await getServicesFromCalendarId(calendarId);
    const services = Object.values(servicesKirby);

    console.log(services);

    const today = dayjs().format('YYYY-MM-DD');

    if (services !== undefined) {
        availabilities = await getAvailableSlots(today, services[0].id, calendarId);
    }

    console.log(availabilities);

    return { content, services, calendarId, availabilities };
};

export const actions = {
    default: async ({ request }) => {
        const { email, firstname, lastname, phone, providerId, serviceId, notes, dateAndSlotAndDuration } = await getFormData(request);
        const { start, end } = createDates(dateAndSlotAndDuration);

        const appointment = await createAppointment(
            start,
            end,
            serviceId,
            providerId,
            notes,
            firstname,
            lastname,
            phone,
            email
        );

        console.log(appointment);

        return { appointment };
    },
} satisfies Actions;

const getFormData = async (request) => {
    const data            = await request.formData();
    const email           = data.get('email');
    const firstname       = data.get('firstname');
    const lastname        = data.get('lastname');
    const phone           = data.get('phone');
    const providerId      = data.get('providerId');
    const serviceId       = data.get('serviceId');
    const notes           = data.get('notes');
    const dateAndSlotAndDuration     = { date: data.get('date'), slot: data.get('slot'), duration: data.get('duration') };
    return { email, firstname, lastname, phone, providerId, serviceId, notes, dateAndSlotAndDuration };
}

const createDates = ({ date, slot, duration }) => {
    const start = dayjs(`${date}T${slot}:00`);
    console.log(start);

    // Create end date from start plus duration
    const end = start.add(duration, 'minute');

    // Format the date to be compatible with easyappointments
    const formattedStart = start.format('YYYY-MM-DD HH:mm:ss');
    const formattedEnd = end.format('YYYY-MM-DD HH:mm:ss');

    return { start: formattedStart, end: formattedEnd };
};
