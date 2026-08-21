import type { RequestHandler } from './$types';
import { CMS_SERVER_BASE_URL, toInternalUrl } from '$lib/server/cms';
import { PUBLIC_CMS_BASE_URL } from '$env/static/public';

// The published menu PDF is served by the foodlab plugin, its filename changes
// on every publication, so resolve the current URL via the API.
export const GET: RequestHandler = async ({ fetch }) => {
	const apiRes = await fetch(`${CMS_SERVER_BASE_URL}/api/restaurant`);
	if (!apiRes.ok) {
		return new Response(null, { status: 502 });
	}

	const page = await apiRes.json();
	const pdfUrl: string | undefined = page.lab?.btn?.link;

	if (!pdfUrl || !pdfUrl.startsWith(`${PUBLIC_CMS_BASE_URL}/api/restaurant/media/`)) {
		return new Response(null, { status: 404 });
	}

	const response = await fetch(toInternalUrl(pdfUrl));

	if (!response.ok) {
		return new Response(null, { status: response.status });
	}

	const headers = new Headers();
	const contentType = response.headers.get('content-type');
	if (contentType) headers.set('content-type', contentType);
	const contentDisposition = response.headers.get('content-disposition');
	if (contentDisposition) headers.set('content-disposition', contentDisposition);
	headers.set('cache-control', 'public, max-age=3600');

	return new Response(response.body, { headers });
};
