import type { Handle } from '@sveltejs/kit';
import { env } from '$env/dynamic/private';

// Optional HTTP Basic auth, used to keep preprod off the public web.
// Set BASIC_AUTH=user:password (see shared/deploy.env); leaving it unset —
// as on production — makes this hook a no-op.
// /health stays open so the container healthcheck keeps working.
const expected = env.BASIC_AUTH
	? `Basic ${Buffer.from(env.BASIC_AUTH).toString('base64')}`
	: '';

export const handle: Handle = async ({ event, resolve }) => {
	if (
		expected &&
		event.url.pathname !== '/health' &&
		event.request.headers.get('authorization') !== expected
	) {
		return new Response('Authentication required', {
			status: 401,
			headers: {
				'www-authenticate': 'Basic realm="Restricted", charset="UTF-8"',
				'cache-control': 'no-store'
			}
		});
	}

	return resolve(event);
};
