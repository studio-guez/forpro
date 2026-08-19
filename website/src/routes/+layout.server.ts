import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { Global } from '$lib/interfaces/global';
import type { LayoutServerLoad } from './$types';

export const load: LayoutServerLoad = async () => {
	const request = new Request(`${CMS_SERVER_BASE_URL}/global.json`, {
		headers: getHeaders()
	});

	const global = await fetchFromAPI<Global>(request, 'Failed to load global data');

	return {
		header: global?.header ?? null,
		favicon: global?.favicon ?? null
	};
};
