import type { RequestHandler } from './$types';

// Liveness probe for the container healthcheck: must not depend on the CMS.
// A standalone endpoint also skips the layout load, which fetches global.json.
export const GET: RequestHandler = () =>
	new Response('ok', { headers: { 'cache-control': 'no-store' } });
