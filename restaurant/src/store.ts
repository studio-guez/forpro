import {type Writable, writable} from "svelte/store";
import type {ISiteInfo} from "$lib/interfaces/cmsApiResponse";

export const menuIsOpen = writable(false)

export const siteInfo: Writable<ISiteInfo | null> = writable(null)
