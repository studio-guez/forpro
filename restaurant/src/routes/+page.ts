import { variables } from '$lib/utils/constants';
import type { PageLoad } from './$types';

export const load: PageLoad = async ({ fetch }) => {
	const res = await fetch(`${variables.CMS_BASE_URL}/api/restaurant`);
	const page = await res.json();

	return { page, menu: page.menu };
};
