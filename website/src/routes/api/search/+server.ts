import { json } from '@sveltejs/kit';
import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { SearchGroup, SearchResponse } from '$lib/interfaces/search';
import type { RequestHandler } from './$types';

const GROUPS: SearchGroup[] = ['all', 'pages', 'events', 'projects'];
const PAGE_SIZE = 10;

const empty = (query: string, group: SearchGroup): SearchResponse => ({
	query,
	group,
	offset: 0,
	counts: { all: 0, pages: 0, events: 0, projects: 0 },
	total: 0,
	hasMore: false,
	results: []
});

// Proxies the CMS search so the browser talks to the site origin only, and the
// request can take the internal Docker network like every other CMS fetch.
export const GET: RequestHandler = async ({ url }) => {
	const query = (url.searchParams.get('q') ?? '').trim().slice(0, 100);
	const requested = url.searchParams.get('group') as SearchGroup | null;
	const group = requested && GROUPS.includes(requested) ? requested : 'all';
	const offset = Math.max(Number(url.searchParams.get('offset')) || 0, 0);

	if (query.length < 2) return json(empty(query, group));

	const params = new URLSearchParams({
		q: query,
		group,
		offset: String(offset),
		limit: String(PAGE_SIZE)
	});
	const request = new Request(`${CMS_SERVER_BASE_URL}/search.json?${params}`, {
		headers: getHeaders()
	});

	const data = await fetchFromAPI<SearchResponse>(request, `Search failed for "${query}"`);

	return json(data ?? empty(query, group));
};
