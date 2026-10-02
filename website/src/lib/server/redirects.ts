import { CMS_SERVER_BASE_URL } from '$lib/server/cms';
import { fetchFromAPI, getHeaders } from '$lib/utils/shared';

interface RedirectTarget {
	/** Current path of the target page; `/` for the home page. */
	readonly url: string;
}

/**
 * The path an editor redirected `path` to (CMS site > Redirections), or null.
 * Only ask once the path has no page: the CMS does not check, so asking first
 * would let a stale entry shadow a live page.
 */
export const findRedirect = async (path: string): Promise<string | null> => {
	const request = new Request(
		`${CMS_SERVER_BASE_URL}/redirect.json?${new URLSearchParams({ path })}`,
		{
			headers: getHeaders()
		}
	);

	const target = await fetchFromAPI<RedirectTarget>(
		request,
		`Failed to resolve redirect for "${path}"`
	);

	return target?.url ?? null;
};

/**
 * The target is resolved from a page reference on every request, so it moves
 * when the page does. A browser-cached 301 would not: it would keep sending
 * returning visitors to the path the page had on their first visit.
 */
export const REDIRECT_HEADERS = { 'cache-control': 'no-store' } as const;
