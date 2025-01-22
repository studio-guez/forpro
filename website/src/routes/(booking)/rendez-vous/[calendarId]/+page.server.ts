import type { Actions, PageServerLoad } from './$types';
import { variables } from "$lib/utils/constants";
import type {BookingCMSResponse, Slot} from "$lib/interfaces/variables";
import {
    createAppointment,
    getCalendarOptions,
    getSchedulesFromCalendarId,
    getServicesFromCalendarId,
} from "$lib/utils/booking/api";
import {fail, redirect} from "@sveltejs/kit";
import {getEachSlotByDayBetweenTwoDates} from "./utils";
import dayjs from "dayjs";

const REQUIRED = 'required';
export const prerender = false;

export const load: PageServerLoad = async ({ params, fetch }) => {
    const cmsBookingUrl = `${variables.CMS_BASE_URL}/booking`;
    const calendarId = params.calendarId;

    const res = await fetch(cmsBookingUrl);
    const content: BookingCMSResponse = await res.json();

    if( !content.bookingIsActive ) redirect(302, '/rendez-vous-inactif');

    const options = await getCalendarOptions(calendarId);

    const servicesKirby = await getServicesFromCalendarId(calendarId);
    const schedules = await getSchedulesFromCalendarId(calendarId);

    const services = Object.values(servicesKirby);

    //todo: eachSlotByDayBetweenTwoDates need to move to client render?
    const minDaysBeforeAppointment = options.minDaysBeforeRdvs ?? 1;
    const today = dayjs();
    const startDateDayJs = today.add(minDaysBeforeAppointment, 'day');
    const startDate = startDateDayJs.toDate();

    const endDate = startDateDayJs.add(1, 'month').toDate();

    const eachSlotByDayBetweenTwoDates = await getEachSlotByDayBetweenTwoDates(startDate, endDate, calendarId, services[0]?.id, fetch)

    return { content, services, calendarId, schedules, options, startDate, endDate, eachSlotByDayBetweenTwoDates };
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
