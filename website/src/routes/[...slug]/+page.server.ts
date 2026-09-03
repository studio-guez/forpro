import { error, redirect } from '@sveltejs/kit';
import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { CmsContent } from '$lib/interfaces/content';
import type { PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ params, url }) => {
	const path = params.slug ?? '';
	const isHome = path === '' || path === 'home';

	// The query string travels with the request: the index templates paginate
	// their list, so they need the filters to serve the page the URL asks for
	// rather than an unfiltered one the browser would immediately replace.
	// Templates that take no filters ignore it.
	const query = url.search;

	// The CMS resolves this against `virtualPath`, so the full path is needed:
	// pages nest both through real Kirby parents and through the `parentPage` field.
	const request = new Request(
		`${CMS_SERVER_BASE_URL}/pages/${isHome ? 'home' : path}.json${query}`,
		{ headers: getHeaders() }
	);

	const page = await fetchFromAPI<CmsContent>(request, `Failed to load page "${path}"`);

	if (!page) error(404, 'Page introuvable');

	// Enforce the canonical path; home lives at the root, not /home. The query
	// string carries the filters, so it has to survive the redirect.
	const canonicalPath = isHome ? '' : page.path;
	if (path !== canonicalPath) redirect(301, `/${canonicalPath}${query}`);

	return { page };
};
