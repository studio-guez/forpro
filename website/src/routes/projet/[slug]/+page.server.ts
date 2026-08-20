import { fetchFromAPI } from '$lib/utils/shared';
import { type IProjectPage } from '$lib/interfaces/cmsApiResponse';
import { variables } from '$lib/utils/constants';
import type { PageServerLoad } from './$types';

export const prerender = false;

export const load: PageServerLoad = async ({ params }) => {
	const request = new Request(
		`${variables.CMS_BASE_URL}/api/project/${encodeURIComponent(params.slug)}`,
		{
			method: 'GET'
		}
	);

	return await fetchFromAPI<IProjectPage>(request, 'Failed to fetch project data');
};
