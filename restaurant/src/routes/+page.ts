import { variables } from '$lib/utils/constants';
import { error } from '@sveltejs/kit';
import type { PageLoad } from './$types';

export const load: PageLoad = async ({ fetch }) => {
	const res = await fetch(`${variables.CMS_BASE_URL}/api/restaurant`);
	if (!res.ok) error(502, 'Impossible de charger le contenu du site.');

	let page;
	try {
		page = await res.json();
	} catch {
		error(502, 'Impossible de charger le contenu du site.');
	}

	return { page, menu: page.menu };
};
