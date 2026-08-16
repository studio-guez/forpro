import { variables } from '$lib/utils/constants';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { Global } from '$lib/interfaces/global';
import type { LayoutServerLoad } from './$types';

export const load: LayoutServerLoad = async () => {
	const request = new Request(`${variables.CMS_BASE_URL}/global.json`, {
		headers: getHeaders()
	});

	const global = await fetchFromAPI<Global>(request, 'Failed to load global data');

	return {
		header: global?.header ?? null,
		favicon: global?.favicon ?? null
	};
};
