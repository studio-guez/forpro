import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import type { RequestHandler } from '@sveltejs/kit';

// Lists frontend URLs, so it has to be served from the frontend origin.
export const GET: RequestHandler = async ({ fetch }) => {
	const response = await fetch(`${CMS_SERVER_BASE_URL}/llms.txt`);
	const text = await response.text();

	return new Response(text, {
		headers: { 'Content-Type': 'text/plain; charset=utf-8' }
	});
};
