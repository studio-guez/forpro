import type { PageServerLoad } from "./$types";
import {error, redirect} from "@sveltejs/kit";
import {confirmAppointment} from "$lib/utils/booking/api";

export const load: PageServerLoad = async ({params, fetch}) => {
    const eventId = params.eventId;
    const resp = await confirmAppointment(eventId);

    console.log(resp);

    switch (resp.state) {
        case 'confirmed':
            redirect(302, '/confirm/valid');
            break;
        case 'already_confirmed':
            redirect(302, '/confirm/already-confirmed');
            break;
        case 'invalid':
            redirect(302, '/confirm/invalid');
            break;
        default:
            error(404, {
                message: 'Not found',
            });
    }
};
