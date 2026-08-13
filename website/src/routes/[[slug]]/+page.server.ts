import { error } from '@sveltejs/kit';
import { variables } from '$lib/utils/constants';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { Page } from '$lib/interfaces/page';
import type { PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ params }) => {
	if (params.slug === 'home') error(404, 'Page introuvable');

	const slug = params.slug ?? 'home';

	const request = new Request(`${variables.CMS_BASE_URL}/pages/${slug}.json`, {
		headers: getHeaders()
	});

	const page = await fetchFromAPI<Page>(request, `Failed to load page "${slug}"`);

	if (!page) error(404, 'Page introuvable');

	return { page };
};
