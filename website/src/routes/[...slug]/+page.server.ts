import { error, redirect } from '@sveltejs/kit';
import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { CmsContent } from '$lib/interfaces/content';
import type { PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ params, url }) => {
	const path = params.slug ?? '';
	const isHome = path === '' || path === 'home';

	// The query string is forwarded: the index templates paginate on it to serve the page the URL asks for.
	const query = url.search;

	const request = new Request(
		`${CMS_SERVER_BASE_URL}/pages/${isHome ? 'home' : path}.json${query}`,
		{ headers: getHeaders() }
	);

	const page = await fetchFromAPI<CmsContent>(request, `Failed to load page "${path}"`);

	if (!page) error(404, 'Page introuvable');

	// The query string carries the filters, so it has to survive the canonical redirect.
	const canonicalPath = isHome ? '' : page.path;
	if (path !== canonicalPath) redirect(301, `/${canonicalPath}${query}`);

	return { page };
};
