import {type Writable, writable} from "svelte/store";
import type {ISiteInfo} from "$lib/interfaces/cmsApiResponse";

export const linkTreeIsOpen = writable(false)

export const menuIsOpen = writable(false)

export const resaButtonIsHidden = writable(false)

export const siteInfo: Writable<ISiteInfo | null> = writable(null)
