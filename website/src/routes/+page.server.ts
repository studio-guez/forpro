import {fetchFromAPI} from "$lib/utils/shared";
import {type IPage} from "$lib/interfaces/cmsApiResponse";
import {variables} from "$lib/utils/constants";
import type {PageServerLoad} from "../../.svelte-kit/types/src/routes/$types";

export const prerender = false;

export const load: PageServerLoad = async () => {

    const request = new Request(`${variables.CMS_BASE_URL}/home.json`, {
        method: 'GET',
    })

    return await fetchFromAPI<IPage>(request, 'Failed to fetch page data')
}
