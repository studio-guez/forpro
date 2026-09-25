export interface TaxonomyTerm {
	readonly slug: string;
	readonly title: string;
	readonly color: string | null;
}

/**
 * A term qualified by the taxonomy it comes from, so terms merged across
 * taxonomies stay distinct even when their slugs collide (e.g. the `makerlab`
 * program and resource). Built by `mergeTerms()`.
 */
export interface KeyedTerm extends TaxonomyTerm {
	readonly key: string;
}

/**
 * A term as offered in a filter bar. Two-level taxonomies (e.g. publics) carry
 * their sub-terms, which are only offered once the parent term is selected.
 */
export interface TaxonomyFilterTerm extends TaxonomyTerm {
	readonly children: TaxonomyTerm[];
}
