// Pill styling shared by `CtaLink` and every control that has to look identical to it
// (`ShareButton`). It lives here because the two render different elements — an anchor
// for navigation, a button for an action — so the classes cannot be shared by wrapping.
//
// The colour is a prop, so it is passed as the `--color-cta` custom property by the
// caller (`style:--color-cta={color}`) and read back through Tailwind's arbitrary
// property syntax, which keeps every class a literal the scanner can find.

export type CtaSize = 'md' | 'lg';

// `max-w-full` keeps the pill inside its container so a long label can be truncated by
// `CTA_LABEL` instead of overflowing the fixed-height pill onto a second line.
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
