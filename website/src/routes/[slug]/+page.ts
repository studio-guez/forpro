import {fetchFromAPI} from "$lib/utils/shared";
import {type IPage} from "$lib/interfaces/cmsApiResponse";
import {CMS_BASE_URL} from "$lib/utils/constants";
import type {PageLoad} from "../../../.svelte-kit/types/src/routes/[slug]/$types";

export const prerender = false;

export const load: PageLoad = async ({params}) => {

    const request = new Request(`${CMS_BASE_URL}${params.slug}.json`, {
        method: 'GET',
    })

    return await fetchFromAPI<IPage>(request, 'Failed to fetch page data')
}
