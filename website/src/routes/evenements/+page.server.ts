import {error} from "@sveltejs/kit";
import {fetchFromAPI} from "$lib/utils/shared";
import {type IPage} from "$lib/interfaces/cmsApiResponse";
import {variables} from "$lib/utils/constants";
import type {PageServerLoad} from "../../../.svelte-kit/types/src/routes/[slug]/$types";

export const prerender = false;

export const load: PageServerLoad = async ({params}) => {

    const request = new Request(`${variables.CMS_BASE_URL}/evenements.json`, {
        method: 'GET',
    })

    const data = await fetchFromAPI<IPage>(request, 'Failed to fetch page data')

    if (!data) error(502, {message: 'Impossible de charger les événements.'})

    return data
}
