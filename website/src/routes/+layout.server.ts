import type {ISiteInfo} from "$lib/interfaces/cmsApiResponse";
import {variables} from "$lib/utils/constants";
import {error} from "@sveltejs/kit";
import {fetchFromAPI} from "$lib/utils/shared";
import type {PageServerLoad} from "../../.svelte-kit/types/src/routes/$types";

export const prerender = false;

export const load: PageServerLoad = async () => {

    const request = new Request(`${variables.CMS_BASE_URL}/site-info.json`, {
        method: 'GET',
    })

    const data = await fetchFromAPI<ISiteInfo>(request, 'Failed to fetch page data')

    if (!data) error(502, {message: 'Impossible de charger les informations du site.'})

    return data
}
