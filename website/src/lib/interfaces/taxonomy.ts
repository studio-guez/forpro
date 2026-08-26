export interface TaxonomyTerm {
	readonly slug: string;
	readonly title: string;
	readonly color: string | null;
}

/**
 * A term as offered in a filter bar. Two-level taxonomies (e.g. publics) carry
 * their sub-terms, which are only offered once the parent term is selected.
 */
export interface TaxonomyFilterTerm extends TaxonomyTerm {
	readonly children: TaxonomyTerm[];
}
