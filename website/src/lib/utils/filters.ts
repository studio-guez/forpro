import { browser } from '$app/environment';
import { replaceState } from '$app/navigation';
import type { TaxonomyFilterTerm, TaxonomyTerm } from '$lib/interfaces/taxonomy';

// Lowercased, accent-insensitive text, so "evenement" matches "événement".
export const normalizeText = (value: string): string =>
	value
		.toLowerCase()
		.normalize('NFD')
		.replace(/\p{Diacritic}/gu, '');

/**
 * A URL-safe id for a piece of text, so it can be deep-linked to (`?question=…`).
 * Accents are folded first, so "Où s'inscrire ?" becomes "ou-s-inscrire".
 */
export const slugify = (value: string): string =>
	normalizeText(value)
		.replace(/[^a-z0-9]+/g, '-')
		.replace(/^-+|-+$/g, '');

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
 * used by at least one item, so filters never lead to an empty result. A parent
 * term is kept as soon as it or one of its sub-terms is used.
 */
export const filterUsedTerms = (
	terms: TaxonomyFilterTerm[],
	used: Iterable<string>
): TaxonomyFilterTerm[] => {
	const usedSlugs = new Set(used);
	return terms
		.map((term) => ({ ...term, children: term.children.filter((c) => usedSlugs.has(c.slug)) }))
		.filter((term) => usedSlugs.has(term.slug) || term.children.length > 0);
};

// Drops slugs coming from the URL that no longer exist, so counters stay
// accurate. A sub-term is only kept while its parent is selected, since that is
// the only state in which the UI offers it.
export const keepKnownSlugs = (selected: string[], terms: TaxonomyFilterTerm[]): string[] =>
	selected.filter((slug) => {
		if (terms.some((term) => term.slug === slug)) return true;
		const parent = terms.find((term) => term.children.some((child) => child.slug === slug));
		return parent !== undefined && selected.includes(parent.slug);
	});

/**
 * Turns a selection into the slugs an item may be tagged with to match it:
 * a selected parent term stands for all of its sub-terms, unless some of them
 * are selected too, in which case the selection is narrowed down to those.
 */
export const expandSelection = (selected: string[], terms: TaxonomyFilterTerm[]): string[] => {
	const slugs = new Set<string>();

	for (const slug of selected) {
		slugs.add(slug);
		const parent = terms.find((term) => term.slug === slug);
		if (!parent) continue;

		const narrowed = parent.children.filter((child) => selected.includes(child.slug));
		for (const child of narrowed.length > 0 ? narrowed : parent.children) slugs.add(child.slug);
	}

	return [...slugs];
};

// A comma-separated URL parameter (`?programs=a,b`) as a list of slugs.
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
