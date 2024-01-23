import {fetchAPIPageContent} from "$lib/utils/shared";
import {type IPage} from "$lib/interfaces/cmsApiResponse";

export async function  load(): Promise<IPage> {
    return await fetchAPIPageContent('page-exemple') satisfies IPage
}
