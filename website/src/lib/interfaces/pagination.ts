/**
 * One page of a filtered index list, as served by `/api/list/<kind>` and
 * embedded — unfiltered, first page — in the index page's own payload so the
 * server-rendered markup needs no fetch.
 *
 * The CMS builds this envelope in `Utils::paginate()`; the three index lists
 * (past events, projects, missions) share it.
 */
export interface PaginatedList<T> {
	readonly offset: number;
	/** Items matching the current filters: what `items` is sliced out of. */
	readonly total: number;
	readonly hasMore: boolean;
	readonly items: T[];
}
