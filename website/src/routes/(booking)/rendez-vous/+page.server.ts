import type {PageServerLoad} from './$types';
import {redirect} from "@sveltejs/kit";

export const load: PageServerLoad = async ({ params, fetch }) => {
    redirect(302, '/rendez-vous/1');
};
