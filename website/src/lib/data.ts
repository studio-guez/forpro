import {variables} from "$lib/utils/constants";
import {fetchFromAPI} from "$lib/utils/shared";
import type {ISiteInfo} from "$lib/interfaces/cmsApiResponse";

export async function getItems() {
    const request = new Request(`${variables.CMS_BASE_URL}/site-info.json`, {
        method: 'GET',
    })

    return await fetchFromAPI<ISiteInfo>(request, 'Failed to fetch page data')
}
