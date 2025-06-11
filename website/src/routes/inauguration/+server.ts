import type { RequestHandler } from '@sveltejs/kit'

export const GET: RequestHandler = () => {
    return new Response(null, {
        status: 302,
        headers: {
            Location: 'https://invitation.infomaniak.com/forms/i/demo?inv=7bcb87c7428790c102dc96bf122ec06c569f3d584eecea56744f610a2b00dbbb#/'
        }
    })
}
