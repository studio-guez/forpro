import type { RequestHandler } from '@sveltejs/kit'

export const GET: RequestHandler = () => {
    return new Response(null, {
        status: 302,
        headers: {
            Location: 'https://framaforms.org/inauguration-de-forpro-1750417762'
        }
    })
}
