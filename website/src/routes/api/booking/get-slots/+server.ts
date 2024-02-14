import { getAvailableSlots } from '$lib/utils/booking/api';
import { json } from '@sveltejs/kit'

export async function POST(event) {
    const data = await event.request.formData()
    
    const calendarId = data.get('calendarId');
    const selectedServiceId = data.get('selectedServiceId');
    const selectedDate = data.get('selectedDate')
    
    const slots = await getAvailableSlots(selectedDate, selectedServiceId, calendarId);
    return json(slots);
}