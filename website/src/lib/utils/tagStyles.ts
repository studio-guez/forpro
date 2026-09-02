// Pill styling shared by every taxonomy term tag: the read-only ones listed by
// `TermTags` and the toggles rendered by `FilterTags`. It lives here because the two
// render different elements — a list item for reading, a button for filtering — so the
// classes cannot be shared by wrapping.
//
// The colour comes from the term, so it is passed as the `--term-color` custom property
// by the caller (`style:--term-color={termColor(term)}`) and read back through Tailwind's
// arbitrary property syntax, which keeps every class a literal the scanner can find.

export type TagSize = 'sm' | 'md' | 'lg';

export const TAG_BASE = 'leading-none text-trim transition-colors';

export const tagSizeClasses: Record<TagSize, string> = {
	sm: 'text-caption px-3 py-1.5 rounded-lg',
	md: 'text-label px-4.5 py-2 rounded-full border-2 border-(--term-color)',
	lg: 'text-body-2 px-4.5 py-2.5 rounded-full border-2 border-(--term-color)'
};

/**
 * Selected fills the pill with the term colour, unselected keeps it as an outline.
 * `sm` is the card badge: it reads over a cover image, so it is always filled.
 */
export const tagColorClasses = (size: TagSize, isSelected: boolean): string =>
	isSelected || size === 'sm'
		? 'bg-(--term-color) text-white'
		: 'bg-transparent text-(--term-color)';
