import { browser } from '$app/environment';
import { replaceState } from '$app/navigation';
import type { TaxonomyTerm } from '$lib/interfaces/taxonomy';

// Lowercased, accent-insensitive text, so "evenement" matches "événement".
export const normalizeText = (value: string): string =>
	value
		.toLowerCase()
		.normalize('NFD')
		.replace(/\p{Diacritic}/gu, '');

// Rich-text fields are stored as HTML; only their text content is searchable.
export const stripTags = (html: string | null | undefined): string =>
	(html ?? '').replace(/<[^>]*>/g, ' ');

/**
 * True when the whole query, trimmed and normalized, is found as a single
 * phrase in the given fields. An empty query matches everything, so callers
 * can pass it through.
 */
export const matchesSearch = (query: string, fields: (string | null | undefined)[]): boolean => {
	const normalizedQuery = normalizeText(query.trim());
	if (normalizedQuery === '') return true;

	return normalizeText(fields.filter(Boolean).join(' ')).includes(normalizedQuery);
};

// True when the item carries none of the selected terms (an empty selection matches everything).
export const matchesTerms = (selected: string[], terms: TaxonomyTerm[]): boolean =>
	selected.length === 0 || terms.some((term) => selected.includes(term.slug));

/**
 * Keeps the taxonomy order defined in the CMS but only offers terms actually
 * used by at least one item, so filters never lead to an empty result.
 */
export const filterUsedTerms = (terms: TaxonomyTerm[], used: Iterable<string>): TaxonomyTerm[] => {
	const usedSlugs = new Set(used);
	return terms.filter((term) => usedSlugs.has(term.slug));
};

// Drops slugs coming from the URL that no longer exist, so counters stay accurate.
export const keepKnownSlugs = (selected: string[], terms: TaxonomyTerm[]): string[] =>
	selected.filter((slug) => terms.some((term) => term.slug === slug));

// A comma-separated URL parameter (`?domains=a,b`) as a list of slugs.
export const parseListParam = (value: string | null): string[] =>
	(value ?? '').split(',').filter(Boolean);

/**
 * Mirrors search + filters into the query string without triggering navigation,
 * so a filtered view can be shared or reloaded.
 */
export const syncQueryString = (params: Record<string, string | string[]>): void => {
	const parts = Object.entries(params)
		.map(([key, value]) => [key, Array.isArray(value) ? value.join(',') : value.trim()] as const)
		.filter(([, value]) => value !== '')
		.map(([key, value]) => `${key}=${encodeURIComponent(value)}`);

	const query = parts.length > 0 ? `?${parts.join('&')}` : '';
	if (!browser || window.location.search === query) return;

	replaceState(`${window.location.pathname}${query}`, {});
};
