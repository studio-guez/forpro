import { error, redirect } from '@sveltejs/kit';
import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import { renderPageMarkdown } from '$lib/server/markdown';
import type { CmsContent } from '$lib/interfaces/content';
import type { PaginatedList } from '$lib/interfaces/pagination';
import type { AgendaEventCard, ProjetCard } from '$lib/interfaces/page';
import type { MissionCard } from '$lib/interfaces/missions';
import type { RequestHandler } from './$types';

/** The most a CMS list route will return at once (it clamps `limit` to 50). */
const PAGE_SIZE = 50;

/**
 * Every item of a paginated CMS list, not just the first page the page payload
 * carries.
 *
 * The HTML index pages paginate because a visitor scrolls for more; a reader of
 * the Markdown has no way to ask, so the whole list travels at once. The
 * requests take the internal Docker network, and stop as soon as the CMS says
 * there is nothing left — or hands back nothing, which would otherwise loop.
 */
async function fetchAllItems<T>(endpoint: string, path: string): Promise<T[]> {
	const items: T[] = [];

	for (let offset = 0; ; offset += PAGE_SIZE) {
		const params = new URLSearchParams({
			path,
			offset: String(offset),
			limit: String(PAGE_SIZE)
		});

		const request = new Request(`${CMS_SERVER_BASE_URL}/${endpoint}?${params}`, {
			headers: getHeaders()
		});

		const list = await fetchFromAPI<PaginatedList<T>>(
			request,
			`Failed to page "${endpoint}" for "${path}"`
		);

		if (!list || list.items.length === 0) break;

		items.push(...list.items);

		if (!list.hasMore) break;
	}

	return items;
}

/**
 * An index page payload with its list filled in. Every other template already
 * ships everything it has.
 */
async function withCompleteLists(page: CmsContent): Promise<CmsContent> {
	switch (page.template) {
		case 'events':
			return {
				...page,
				pastEvents: {
					...page.pastEvents,
					items: await fetchAllItems<AgendaEventCard>('past-events.json', page.path)
				}
			};

		case 'projects':
			return {
				...page,
				projects: {
					...page.projects,
					items: await fetchAllItems<ProjetCard>('projects.json', page.path)
				}
			};

		case 'missions':
			return {
				...page,
				missions: {
					...page.missions,
					items: await fetchAllItems<MissionCard>('missions.json', page.path)
				}
			};

		default:
			return page;
	}
}

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

	return new Response(renderPageMarkdown(await withCompleteLists(page), url.origin), {
		headers: { 'Content-Type': 'text/markdown; charset=utf-8' }
	});
};
