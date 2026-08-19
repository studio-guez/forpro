import type { RequestHandler } from './$types';

// Liveness probe for the container healthcheck: must not depend on the CMS.
export const GET: RequestHandler = () =>
	new Response('ok', { headers: { 'cache-control': 'no-store' } });
