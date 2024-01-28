import type {Actions, PageServerLoad} from './$types';
import {CMS_BASE_URL} from "$lib/utils/constants";
import type {BookingCMSResponse} from "$lib/interfaces/variables";
import {
    createAppointment,
    createCustomer,
    getAvailableSlots, getCustomer,
    getServicesFromProvider
} from "$lib/utils/easyappointments/api";

export const prerender = false;

export const load: PageServerLoad = async ({ params, fetch }) => {
    const cmsBookingUrl = `${CMS_BASE_URL}/booking`
    const providerId = params.providerId;

    const res = await fetch(cmsBookingUrl);
    const content: BookingCMSResponse = await res.json();

    const services = await getServicesFromProvider(providerId);

    const today = new Date().toDateString();
    const availabilities = await getAvailableSlots(today, services[0].id, providerId);

    return { content, services, providerId, availabilities };
};

export const actions = {
    default: async ({ cookies, request }) => {
        const { email, firstname, lastname, phone, providerId, serviceId, notes, dateAndSlotAndDuration } = await getFormData(request);
        const { start, end } = createDates(dateAndSlotAndDuration);

        let customer = await getCustomer(email);
        if (customer) {
            customer = customer.find((c) => c.email === email);
        }

        if (customer === undefined) {
            customer = await createCustomer(email, firstname, lastname, phone);
        }

        const appointment = await createAppointment(
            start,
            end,
            serviceId,
            providerId,
            customer.id,
            notes
        );

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
    let start = new Date(`${date}T${slot}:00`)

    // Create end date from the timestamp of start
    let end = new Date(start.getTime() + duration * 60000);  // Convert minutes to milliseconds

    // Format iso string to be compatible with easyappointments
    let formattedStart = start.toISOString().replace(/\.\d{3}Z$/, '').replace('T', ' ');
    let formattedEnd = end.toISOString().replace(/\.\d{3}Z$/, '').replace('T', ' ');

    return { start: formattedStart, end: formattedEnd };
};
