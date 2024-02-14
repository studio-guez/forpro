import type {PageServerLoad} from './$types';
import {redirect} from "@sveltejs/kit";

export const load: PageServerLoad = async ({ params, fetch }) => {
    redirect(302, '/rendez-vous/87cde363-c651-4add-92c9-abdf44b0ff7f');
};
