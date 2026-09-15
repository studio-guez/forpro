// The colour travels as the `--term-color` custom property so every Tailwind class stays a literal the scanner can find.

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
