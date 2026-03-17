import {error, redirect} from '@sveltejs/kit'
import type { PageServerLoad } from './$types'
import {variables} from "$lib/utils/constants";
import {fetchFromAPI} from "$lib/utils/shared";

export const prerender = false

export const load: PageServerLoad = async ({ params }) => {

  const request = new Request(`${variables.CMS_BASE_URL}/liens-courts/${params.slug}.json`, {
    method: 'GET',
  })

  let data: null | {url: string} = null

  try {
    data = await fetchFromAPI<{url: string}>(request, 'Failed to fetch page data')
  } catch {
    throw error(404, {
      message: "Ce lien court n'éxiste pas."
    })
  }

  if( !data) throw error(404, {
    message: "Ce lien court n'éxiste pas."
  })

  redirect(301, data.url)
}
