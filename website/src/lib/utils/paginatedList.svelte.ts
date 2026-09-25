import type { PaginatedList } from '$lib/interfaces/pagination';

/** How long a filter change waits before it is sent, so typing queues one request, not one per keystroke. */
const DEBOUNCE_MS = 250;

interface Options<R> {
	/** Which list to page through; a key of the `LISTS` table in `/api/list/[kind]`. */
	kind: string;
	/** The index page's `virtualPath`, which the CMS resolves the list against. */
	path: () => string;
	/** The first page embedded in the index page payload, already filtered by the URL. */
	seed: () => R;
	/**
	 * The current filters, as the CMS expects them, and as they appear in the
	 * URL: taxonomy selections travel **raw**. The CMS resolves them itself
	 * (`expandTaxonomySlugs()`), which is what lets it render the first page
	 * server-side from the query string alone.
	 */
	filters: () => Record<string, string>;
}

/**
 * Drives an index list that the CMS serves one page at a time: it starts from
 * the page payload, refetches from the top whenever the filters change, and
 * appends the next page on demand.
 *
 * Must be called during component initialization — it registers `$effect`s and
 * needs their context.
 *
 * The filtering itself is deliberately not mirrored here. A filtered list is
 * sliced server-side, so filtering a page of it in the browser would hide items
 * from a set the visitor can never scroll to.
 */
export const createPaginatedList = <T, R extends PaginatedList<T>>(options: Options<R>) => {
	let fetched = $state<R | null>(null);
	let loadingMore = $state(false);

	const seed = $derived(options.seed());
	const filters = $derived(options.filters());
	const current = $derived(fetched ?? seed);

	const load = async (offset: number, signal?: AbortSignal): Promise<R> => {
		// eslint-disable-next-line svelte/prefer-svelte-reactivity -- built, stringified into the URL and dropped; never read reactively
		const params = new URLSearchParams({
			path: options.path(),
			...filters,
			offset: String(offset)
		});
		const response = await fetch(`/api/list/${options.kind}?${params}`, { signal });
		if (!response.ok) throw new Error(`${response.status} ${response.statusText}`);

		return response.json();
	};

	$effect(() => {
		void seed;
		fetched = null;
	});

	let seeded = true;
	$effect(() => {
		void filters;

		// The embedded first page already matches the URL filters, so the first run fetches nothing.
		if (seeded) {
			seeded = false;
			return;
		}

		const controller = new AbortController();
		const timer = setTimeout(async () => {
			try {
				fetched = await load(0, controller.signal);
			} catch (error) {
				if (error instanceof DOMException && error.name === 'AbortError') return;
				fetched = { ...seed, offset: 0, total: 0, hasMore: false, items: [] };
			}
		}, DEBOUNCE_MS);

		return () => {
			clearTimeout(timer);
			controller.abort();
		};
	});

	return {
		/** The current page of the list, including any list-specific extra keys. */
		get current() {
			return current;
		},
		get items() {
			return current.items;
		},
		/** Items matching the current filters, not the number loaded so far. */
		get total() {
			return current.total;
		},
		get hasMore() {
			return current.hasMore;
		},
		loadMore: async (): Promise<void> => {
			if (loadingMore || !current.hasMore) return;

			loadingMore = true;
			try {
				const data = await load(current.items.length);
				fetched = { ...data, items: [...current.items, ...data.items] };
			} catch {
				// Stop pulling rather than looping on a failing endpoint.
				fetched = { ...current, hasMore: false };
			}
			loadingMore = false;
		}
	};
};
