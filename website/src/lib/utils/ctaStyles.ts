// The colour travels as the `--color-cta` custom property so every Tailwind class stays a literal the scanner can find.

export type CtaSize = 'md' | 'lg';

export const CTA_BASE =
	'font-bold leading-none inline-flex items-center max-w-full rounded-full bg-transparent backdrop-blur-xs transition-colors';

/** Label span: single line, ellipsis when the pill runs out of room. */
export const CTA_LABEL = 'text-trim min-w-0 truncate-x';

export const ctaSizeClasses: Record<CtaSize, string> = {
	md: 'text-base lg:text-lg gap-2 lg:gap-2.5 px-2.25 lg:px-5.5 h-10.5 lg:h-12.5 border-3',
	lg: 'text-base lg:text-2xl gap-2 lg:gap-3 px-2.25 lg:px-7.5 h-10.5 lg:h-17 border-3 lg:border-4'
};

export const ctaIconSizeClasses: Record<CtaSize, string> = {
	md: 'shrink-0 w-6 lg:w-7 h-6 lg:h-7',
	lg: 'shrink-0 w-6 lg:w-9.5 h-6 lg:h-9.5'
};

/** Inverted swaps the pill onto a dark surface: white outline, themed text on hover. */
export const ctaColorClasses = (inverted: boolean): string =>
	inverted
		? 'text-white border-white hover:bg-white hover:text-(--color-cta)'
		: 'text-(--color-cta) border-(--color-cta) hover:bg-(--color-cta) hover:text-white';
