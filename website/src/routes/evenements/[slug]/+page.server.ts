import {fetchFromAPI} from "$lib/utils/shared";
import {type IPage_Event} from "$lib/interfaces/cmsApiResponse";
import {variables} from "$lib/utils/constants";
import type { PageServerLoad } from "../../evenements/[slug]/$types";

export const prerender = false;

export const load: PageServerLoad = async ({params}) => {

    const request = new Request(`${variables.CMS_BASE_URL}/evenements/${params.slug}.json`, {
        method: 'GET',
    })

    return await fetchFromAPI<IPage_Event>(request, 'Failed to fetch page data')
}
