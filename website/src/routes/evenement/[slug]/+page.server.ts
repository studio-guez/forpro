import { fetchFromAPI } from '$lib/utils/shared';
import { type IEventPage } from '$lib/interfaces/cmsApiResponse';
import { variables } from '$lib/utils/constants';
import type { PageServerLoad } from './$types';

export const prerender = false;

export const load: PageServerLoad = async ({ params }) => {
	const request = new Request(
		`${variables.CMS_BASE_URL}/api/event/${encodeURIComponent(params.slug)}`,
		{
			method: 'GET'
		}
	);

	return await fetchFromAPI<IEventPage>(request, 'Failed to fetch event data');
};
