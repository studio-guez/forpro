import type { Theme, Variant } from '$lib/interfaces/page';

/**
 * One palette per theme, describing the default (filled) variant only.
 * The inverted variant is always derived from it, never declared.
 */
export interface ThemePalette {
	/** Card background. */
	bg: string;
	/** Fill of the decorative shapes drawn on top of `bg`. */
	bgContrast: string;
	/** Body text drawn on `bg`. */
	text: string;
	/** Theme colour of the title: pill text on the default variant, pill fill when inverted. */
	title: string;
}

export interface ThemeColors {
	bg: string;
	text: string;
	title: string;
	titleBackground: string;
	bgContrast: string;
	/** Decorative shapes are only drawn on the default variant. */
	showShapes: boolean;
	/** Filled surfaces layered on the card (blobs, badges) keep the theme colours. */
	surface: string;
	surfaceText: string;
	/** Colour of links and controls; `onDark` when they sit on a coloured surface. */
	accent: string;
	onDark: boolean;
}

const WHITE = 'var(--color-white)';

export const themePalettes: Record<Theme, ThemePalette> = {
	default: {
		bg: 'var(--color-blue)',
		bgContrast: 'var(--color-blue-light)',
		text: WHITE,
		title: 'var(--color-blue)',
	},
	campus: {
		bg: 'var(--color-blue)',
		bgContrast: 'var(--color-blue-light)',
		text: WHITE,
		title: 'var(--color-blue)',
	},
	entreprendre: {
		bg: 'var(--color-purple-light)',
		bgContrast: 'var(--color-purple-pale)',
		text: WHITE,
		title: 'var(--color-purple-light)',
	},
	projets_jeunes: {
		bg: 'var(--color-orange)',
		bgContrast: 'var(--color-orange-light)',
		text: WHITE,
		title: 'var(--color-orange)',
	},
	tremplin_jobs: {
		bg: 'var(--color-purple-light)',
		bgContrast: 'var(--color-green)',
		text: WHITE,
		title: 'var(--color-purple-light)',
	},
	soutiens: {
		bg: 'var(--color-blue-light)',
		bgContrast: 'var(--color-pink)',
		text: WHITE,
		title: 'var(--color-pink)',
	},
	cekale: {
		bg: 'var(--color-purple-pale)',
		bgContrast: 'var(--color-purple)',
		text: WHITE,
		title: 'var(--color-purple)',
	},
	la_ref: {
		bg: 'var(--color-purple-pale)',
		bgContrast: 'var(--color-pink)',
		text: WHITE,
		title: 'var(--color-pink)',
	},
	learninglab: {
		bg: 'var(--color-teal)',
		bgContrast: 'var(--color-teal-light)',
		text: WHITE,
		title: 'var(--color-teal)',
	},
	foodlab: {
		bg: 'var(--color-orange-pale)',
		bgContrast: 'var(--color-orange-light)',
		text: 'var(--color-orange)',
		title: 'var(--color-orange)',
	},
	grandlab: {
		bg: 'var(--color-brown)',
		bgContrast: 'var(--color-red)',
		text: WHITE,
		title: 'var(--color-red)',
	},
	makerlab: {
		bg: 'var(--color-beige-light)',
		bgContrast: 'var(--color-beige)',
		text: 'var(--color-grey-dark)',
		title: 'var(--color-grey-dark)',
	},
	factorylab: {
		bg: 'var(--color-teal)',
		bgContrast: 'var(--color-pink)',
		text: WHITE,
		title: 'var(--color-pink)',
	},
};

/**
 * Resolve the colours a block should use:
 * - `default` fills the card with the theme colour and draws the title in the theme
 *   colour on white;
 * - `inverted` sits on white, drops the decorative shapes, reuses the title colour for
 *   the body text and draws the title in white on the theme colour.
 */
export function getThemeColors(theme: Theme, variant: Variant): ThemeColors {
	const palette = themePalettes[theme];

	if (variant === 'inverted') {
		return {
			bg: WHITE,
			text: palette.title,
			title: WHITE,
			titleBackground: palette.title,
			bgContrast: palette.bgContrast,
			showShapes: false,
			surface: palette.bg,
			surfaceText: palette.text,
			accent: palette.title,
			onDark: false,
		};
	}

	const onDark = palette.text === WHITE;

	return {
		bg: palette.bg,
		text: palette.text,
		title: palette.title,
		titleBackground: WHITE,
		bgContrast: palette.bgContrast,
		showShapes: true,
		surface: palette.bg,
		surfaceText: palette.text,
		accent: onDark ? palette.bg : palette.text,
		onDark,
	};
}
