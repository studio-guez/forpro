// Grid shared by the "label + items" sections of the Team and Impressum templates: a
// heading in the first column and the entries spanning the remaining three from `lg` up,
// stacked below that. It lives here because the two templates fill the items grid with
// different elements — a `ul` of members, a `dl` of credits — so the classes cannot be
// shared by wrapping.
//
// The horizontal page padding is left to the caller: Impressum lays these sections out
// directly on the page, Team nests them inside an already padded expandable section.
//
// Mobile collapses to a single 1.5rem gap so the stacked heading, entries and the next
// section keep one rhythm; the taller row gaps only kick in once the columns exist.

export const LABELLED_SECTION = 'grid grid-cols-1 lg:grid-cols-4 gap-x-6 gap-y-4 lg:gap-y-9';

export const LABELLED_SECTION_ITEMS =
	'text-body-2 lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4 lg:gap-y-12';
