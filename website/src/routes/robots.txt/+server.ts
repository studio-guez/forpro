import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import type { RequestHandler } from '@sveltejs/kit';

export const GET: RequestHandler = async ({ fetch }) => {
    const response = await fetch(`${CMS_SERVER_BASE_URL}/robots.txt`);
    const text = await response.text();

    return new Response(text, {
        headers: { 'Content-Type': 'text/plain' }
    });
};
