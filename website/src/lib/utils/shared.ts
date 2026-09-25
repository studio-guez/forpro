import type { KeyedTerm, TaxonomyTerm } from '$lib/interfaces/taxonomy';

export const termColor = (term: TaxonomyTerm): string =>
	term.color ? `var(--color-${term.color})` : 'var(--color-teal)';

/** Flattens terms from several taxonomies, keyed by `<taxonomy>-<slug>`, in insertion order. */
export const mergeTerms = (groups: Record<string, TaxonomyTerm[]>): KeyedTerm[] =>
	Object.entries(groups).flatMap(([taxonomy, terms]) =>
		terms.map((term) => ({ ...term, key: `${taxonomy}-${term.slug}` }))
	);

const headers = new Headers();
headers.append('Content-Type', 'application/json');

export const getHeaders = (): Headers => {
	return headers;
};

const handleError = (errorMsg: string, error: any) => {
	console.error(`${errorMsg}: ${error}`);
};

export const fetchFromAPI = async <T>(request: Request, errorMsg: string): Promise<T | null> => {
	try {
		const response = await fetch(request);
		if (!response.ok) {
			// A 404 is expected (unknown slug / short link), so it is not logged.
			if (response.status !== 404) {
				handleError(
					errorMsg,
					new Error(`${response.status} ${response.statusText} (${request.url})`)
				);
			}
			return null;
		}
		const data = await response.json();

		return data as T;
	} catch (error) {
		handleError(errorMsg, error);
		return null;
	}
};
