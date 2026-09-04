import { error, redirect } from '@sveltejs/kit';
import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import { renderPageMarkdown } from '$lib/server/markdown';
import type { CmsContent } from '$lib/interfaces/content';
import type { RequestHandler } from './$types';

/**
 * The Markdown representation of any page: `/entreprendre/mentorat.md` for
 * `/entreprendre/mentorat`. Advertised from the HTML page with
 * `rel="alternate" type="text/markdown"`, and by the note in llms.txt.
 *
 * It reads the same payload as the HTML route, so the two can never fall out of
 * step, and it mirrors that route's rules: home lives at the root, and a path
 * that is not the page's own redirects to the one that is.
 */
export const GET: RequestHandler = async ({ params, url }) => {
	const path = params.slug ?? '';

	// The home page answers at `/index.md`, not at `/.md`: a path starting with
	// a dot is what dotfile rules on a reverse proxy are written to block, and
	// the proxy in front of production is not ours to check.
	const isHome = path === '' || path === 'home' || path === 'index';

	const request = new Request(`${CMS_SERVER_BASE_URL}/pages/${isHome ? 'home' : path}.json`, {
		headers: getHeaders()
	});

	const page = await fetchFromAPI<CmsContent>(request, `Failed to load page "${path}"`);

	if (!page) error(404, 'Page introuvable');

	const canonicalPath = isHome ? 'index' : page.path;
	if (path !== canonicalPath) redirect(301, `/${canonicalPath}.md`);

	return new Response(renderPageMarkdown(page, url.origin), {
		headers: { 'Content-Type': 'text/markdown; charset=utf-8' }
	});
};
