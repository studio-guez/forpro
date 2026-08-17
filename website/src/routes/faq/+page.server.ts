import { error } from '@sveltejs/kit';
import { variables } from '$lib/utils/constants';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { FaqPage } from '$lib/interfaces/faq';
import type { PageServerLoad } from './$types';

export const load: PageServerLoad = async () => {
	// The FAQ lives at the root of the Kirby content tree (see site.yml).
	const request = new Request(`${variables.CMS_BASE_URL}/faq.json`, {
		headers: getHeaders()
	});

	const page = await fetchFromAPI<FaqPage>(request, 'Failed to load FAQ page');

	if (!page) error(404, 'Page introuvable');

	return { page };
};
