import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { error } from '@sveltejs/kit';
import type { PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ fetch }) => {
	const res = await fetch(`${CMS_SERVER_BASE_URL}/api/restaurant`);
	if (!res.ok) error(502, 'Impossible de charger le contenu du site.');

	let page;
	try {
		page = await res.json();
	} catch {
		error(502, 'Impossible de charger le contenu du site.');
	}

	return { page, menu: page.menu };
};
