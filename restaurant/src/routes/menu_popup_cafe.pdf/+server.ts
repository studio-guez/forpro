import type { RequestHandler } from './$types';
import { CMS_SERVER_BASE_URL } from '$lib/server/cms';

// Path is stable; the file lives at the site level in Kirby.
const CMS_PATH = '/media/site/7a047d9699-1730810358/menu_popup.pdf';

export const GET: RequestHandler = async () => {
	const response = await fetch(`${CMS_SERVER_BASE_URL}${CMS_PATH}`);

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
