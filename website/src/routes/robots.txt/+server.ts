import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { IS_PREPROD } from '$lib/env';
import type { RequestHandler } from '@sveltejs/kit';

export const GET: RequestHandler = async ({ fetch }) => {
    // Preprod must never be crawled, whatever the CMS serves.
    if (IS_PREPROD) {
        return new Response('User-agent: *\nDisallow: /\n', {
            headers: { 'Content-Type': 'text/plain' }
        });
    }

    const response = await fetch(`${CMS_SERVER_BASE_URL}/robots.txt`);
    const text = await response.text();

    return new Response(text, {
        headers: { 'Content-Type': 'text/plain' }
    });
};
