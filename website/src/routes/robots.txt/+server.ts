import { variables } from '$lib/utils/constants';
import type { RequestHandler } from '@sveltejs/kit';

export const GET: RequestHandler = async ({ fetch }) => {
    const response = await fetch(`${variables.CMS_BASE_URL}/robots.txt`);
    const text = await response.text();

    return new Response(text, {
        headers: { 'Content-Type': 'text/plain' }
    });
};
