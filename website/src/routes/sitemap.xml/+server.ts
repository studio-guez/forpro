import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import type { RequestHandler } from '@sveltejs/kit';

export const GET: RequestHandler = async ({ fetch }) => {
	const response = await fetch(`${CMS_SERVER_BASE_URL}/sitemap.xml`);
	const xml = await response.text();

	return new Response(xml, {
		headers: { 'Content-Type': 'application/xml' }
	});
};
