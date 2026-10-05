import { CMS_SERVER_BASE_URL, toInternalUrl } from './cms';
import { PUBLIC_CMS_BASE_URL } from '$env/static/public';

type RestaurantPage = {
	lab?: { btn?: { link?: string } };
	univers?: { popupMenuUrl?: string };
};

const MEDIA_URL_PREFIX = `${PUBLIC_CMS_BASE_URL}/api/restaurant/media/`;

// PDF filenames change on every upload, so the URL is resolved through the restaurant API; only that library's files are proxied.
export async function proxyRestaurantPdf(
	fetch: typeof globalThis.fetch,
	pick: (page: RestaurantPage) => string | undefined
): Promise<Response> {
	const apiRes = await fetch(`${CMS_SERVER_BASE_URL}/api/restaurant`);
	if (!apiRes.ok) {
		return new Response(null, { status: 502 });
	}

	const pdfUrl = pick((await apiRes.json()) as RestaurantPage);

	if (!pdfUrl || !pdfUrl.startsWith(MEDIA_URL_PREFIX)) {
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
}
