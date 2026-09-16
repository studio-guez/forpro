import { error, json } from '@sveltejs/kit';
import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';
import type { PaginatedList } from '$lib/interfaces/pagination';
import type { RequestHandler } from './$types';

/**
 * The paginated index lists, each mapped to its CMS route, the filters it
 * accepts and how many items a page holds. Page sizes live here rather than in
 * the components: the CMS caps `limit` but does not impose one, so this is the
 * single place that decides how much of an archive travels at a time.
 *
 * `empty` carries the keys a given list adds to the shared envelope, so a
 * failed fetch still returns a well-formed payload.
 */
const LISTS = {
	'past-events': {
		endpoint: 'past-events.json',
		params: ['q', 'month'],
		pageSize: 10,
		empty: { matchTotal: 0, months: [] }
	},
	projects: {
		endpoint: 'projects.json',
		params: ['q', 'sectors', 'categories', 'years'],
		pageSize: 12,
		empty: {}
	},
	missions: {
		endpoint: 'missions.json',
		params: ['categories', 'sort'],
		pageSize: 24,
		empty: {}
	}
} as const satisfies Record<
	string,
	{ endpoint: string; params: readonly string[]; pageSize: number; empty: object }
>;

type ListKind = keyof typeof LISTS;

const isListKind = (kind: string): kind is ListKind => kind in LISTS;

/**
 * Proxies a paginated CMS list so the browser talks to the site origin only,
 * and the request can take the internal Docker network like every other CMS
 * fetch. Taxonomy parameters are passed through untouched: the frontend has
 * already expanded them, and the CMS must not expand them again.
 */
export const GET: RequestHandler = async ({ params: routeParams, url }) => {
	if (!isListKind(routeParams.kind)) error(404, 'Liste inconnue');

	const list = LISTS[routeParams.kind];
	const path = url.searchParams.get('path') ?? '';
	const offset = Math.max(Number(url.searchParams.get('offset')) || 0, 0);
	const empty = { offset, total: 0, hasMore: false, items: [], ...list.empty };

	if (path === '') return json(empty);

	const params = new URLSearchParams({
		path,
		offset: String(offset),
		limit: String(list.pageSize)
	});
	for (const name of list.params) params.set(name, url.searchParams.get(name) ?? '');

	const request = new Request(`${CMS_SERVER_BASE_URL}/${list.endpoint}?${params}`, {
		headers: getHeaders()
	});

	const data = await fetchFromAPI<PaginatedList<unknown>>(
		request,
		`List "${routeParams.kind}" failed for "${path}"`
	);

	return json(data ?? empty);
};
