import type {PageServerLoad} from './$types';
import {CMS_BASE_URL} from "$lib/utils/constants";
import type {BookingCMSResponse} from "$lib/interfaces/variables";
import {fetchAvailableSlots, fetchServicesFromProvider} from "$lib/utils/easyappointments/api";

export const load: PageServerLoad = async ({ params, fetch }) => {
    const cmsBookingUrl = `${CMS_BASE_URL}/booking`
    const providerId = params.providerId;

    const res = await fetch(cmsBookingUrl);
    const content: BookingCMSResponse = await res.json();

    const services = await fetchServicesFromProvider(providerId);

    const today = new Date().toDateString();
    const availabilities = await fetchAvailableSlots(today, services[0].id, providerId);
    
    return { content, services, providerId, availabilities };
};
