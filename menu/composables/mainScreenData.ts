import {getCmsBaseUrl} from "~/utils/cmsBaseUrl";

export async function getMainScreenData(): Promise<{filename: string, url: string}[]> {
  const data = await fetch(`${getCmsBaseUrl()}/slider-images`, {
    method: 'GET',
  })

  return data.json()
}
