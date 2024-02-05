import type {ISiteInfo} from "$lib/interfaces/cmsApiResponse";
import {variables} from "$lib/utils/constants";
import {fetchFromAPI} from "$lib/utils/shared";
import type {PageServerLoad} from "../../.svelte-kit/types/src/routes/$types";

export const prerender = false;

export const load: PageServerLoad = async () => {

    const request = new Request(`${variables.CMS_BASE_URL}/site-info.json`, {
        method: 'GET',
    })

    return await fetchFromAPI<ISiteInfo>(request, 'Failed to fetch page data')
}
