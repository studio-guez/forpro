import {useRuntimeConfig} from "#app";

export function getCmsBaseUrl(): string {
  return useRuntimeConfig().public.cmsBaseUrl
}
