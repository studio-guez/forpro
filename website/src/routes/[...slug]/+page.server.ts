import { error, redirect } from '@sveltejs/kit';
import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { CmsContent } from '$lib/interfaces/content';
import type { PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ params }) => {
	const segments = params.slug ? params.slug.split('/') : [];

	// All pages are flat in Kirby; only the last segment is the real slug.
	const slug = segments.at(-1) ?? 'home';

	const request = new Request(`${CMS_SERVER_BASE_URL}/pages/${slug}.json`, {
		headers: getHeaders()
	});

	const page = await fetchFromAPI<CmsContent>(request, `Failed to load page "${slug}"`);

	if (!page) error(404, 'Page introuvable');

	// Enforce the canonical path; home lives at the root, not /home.
	const canonicalPath = slug === 'home' ? '' : page.path;
	if ((params.slug ?? '') !== canonicalPath) redirect(301, `/${canonicalPath}`);

	return { page };
};
