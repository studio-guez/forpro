import { IS_PREPROD } from '$lib/env';
import type { RequestHandler } from '@sveltejs/kit';

export const GET: RequestHandler = ({ url }) => {
	// Preprod must never be crawled.
	const robots = IS_PREPROD
		? `User-agent: *
Disallow: /
`
		: `User-agent: *
Allow: /

Sitemap: ${url.origin}/sitemap.xml
`;

	return new Response(robots, {
		headers: { 'Content-Type': 'text/plain' }
	});
};
