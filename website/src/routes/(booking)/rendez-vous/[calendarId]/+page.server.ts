import type { Actions, PageServerLoad } from './$types';
import { variables } from "$lib/utils/constants";
import type { BookingCMSResponse } from "$lib/interfaces/variables";
import {
    createAppointment,
    getSchedulesFromCalendarId,
    getServicesFromCalendarId,
} from "$lib/utils/booking/api";
import { fail } from "@sveltejs/kit";

const REQUIRED = 'required';
export const prerender = false;

export const load: PageServerLoad = async ({ params, fetch }) => {
    const cmsBookingUrl = `${variables.CMS_BASE_URL}/booking`;
    const calendarId = params.calendarId;
    const res = await fetch(cmsBookingUrl);
    const content: BookingCMSResponse = await res.json();
    const servicesKirby = await getServicesFromCalendarId(calendarId);
    const schedules = await getSchedulesFromCalendarId(calendarId);
    const services = Object.values(servicesKirby);
    return { content, services, calendarId, schedules };
};

function validateInput(value: unknown, name: string, errors: Record<string, unknown>) {
    if (!value || typeof value !== 'string') {
        errors[name] = REQUIRED;
    }
}

export const actions = {
    default: async ({ request }) => {
        const data = await request.formData();
        const errors: Record<string, unknown> = {};

        const formDataEntries = [
            'email',
            'firstname',
            'lastname',
            'phone',
            'calendarId',
            'serviceId',
            'date',
            'slot',
            'subject',
        ];

        formDataEntries.forEach(name => {
            const value = data.get(name);
            validateInput(value, name, errors);
        });

        if (Object.keys(errors).length > 0) {
            const returnData = {
                data: Object.fromEntries(data),
                errors,
            }
            return fail(400, returnData);
        }

        const appointment = await createAppointment(
            data.get('date') as string,
            data.get('slot') as string,
            data.get('subject') as string,
            data.get('serviceId') as string,
            data.get('calendarId') as string,
            {
                firstname: data.get('firstname') as string,
                lastname: data.get('lastname') as string,
                phone: data.get('phone') as string,
                email: data.get('email') as string,
            }
        );

        return { appointment };
    },
} satisfies Actions;
