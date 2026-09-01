import type { Theme, Variant } from '$lib/interfaces/page';

/**
 * One palette per theme, describing the default (filled) variant only — except for the
 * element blobs, whose inverted colours are declared per theme because they sit on the
 * white card and cannot be derived from the filled variant.
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
	/**
	 * Fill of the element blobs on the inverted variant: always a pale tint, since the
	 * blobs sit on the white card and carry dark text.
	 */
	invertedElementsBlobColor: string;
	/** Text drawn inside those blobs: always a dark colour of the theme, never white. */
	invertedElementsTextColor: string;
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
	/** Fill of the element blobs on the inverted variant. */
	invertedElementsBlobColor: string;
	/** Text drawn inside the element blobs on the inverted variant. */
	invertedElementsTextColor: string;
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
		invertedElementsBlobColor: 'var(--color-blue-pale)',
		invertedElementsTextColor: 'var(--color-blue)',
	},
	campus: {
		bg: 'var(--color-blue)',
		bgContrast: 'var(--color-blue-light)',
		text: WHITE,
		title: 'var(--color-blue)',
		invertedElementsBlobColor: 'var(--color-blue-pale)',
		invertedElementsTextColor: 'var(--color-blue)',
	},
	entreprendre: {
		bg: 'var(--color-purple-light)',
		bgContrast: 'var(--color-purple-pale)',
		text: WHITE,
		title: 'var(--color-purple-light)',
		invertedElementsBlobColor: 'var(--color-purple-pale)',
		invertedElementsTextColor: 'var(--color-purple)',
	},
	projets_jeunes: {
		bg: 'var(--color-orange)',
		bgContrast: 'var(--color-orange-light)',
		text: WHITE,
		title: 'var(--color-orange)',
		invertedElementsBlobColor: 'var(--color-orange-pale)',
		invertedElementsTextColor: 'var(--color-orange)',
	},
	tremplin_jobs: {
		bg: 'var(--color-purple-light)',
		bgContrast: 'var(--color-green)',
		text: WHITE,
		title: 'var(--color-purple-light)',
		invertedElementsBlobColor: 'var(--color-purple-pale)',
		invertedElementsTextColor: 'var(--color-purple)',
	},
	soutiens: {
		bg: 'var(--color-blue-light)',
		bgContrast: 'var(--color-pink)',
		text: WHITE,
		title: 'var(--color-pink)',
		invertedElementsBlobColor: 'var(--color-blue-pale)',
		invertedElementsTextColor: 'var(--color-blue)',
	},
	cekale: {
		bg: 'var(--color-purple-pale)',
		bgContrast: 'var(--color-purple)',
		text: WHITE,
		title: 'var(--color-purple)',
		invertedElementsBlobColor: 'var(--color-purple-pale)',
		invertedElementsTextColor: 'var(--color-purple)',
	},
	la_ref: {
		bg: 'var(--color-purple-pale)',
		bgContrast: 'var(--color-pink)',
		text: WHITE,
		title: 'var(--color-pink)',
		invertedElementsBlobColor: 'var(--color-purple-pale)',
		invertedElementsTextColor: 'var(--color-purple)',
	},
	learninglab: {
		bg: 'var(--color-teal)',
		bgContrast: 'var(--color-teal-light)',
		text: WHITE,
		title: 'var(--color-teal)',
		invertedElementsBlobColor: 'var(--color-teal-pale)',
		invertedElementsTextColor: 'var(--color-teal)',
	},
	foodlab: {
		bg: 'var(--color-orange-pale)',
		bgContrast: 'var(--color-orange-light)',
		text: 'var(--color-orange)',
		title: 'var(--color-orange)',
		invertedElementsBlobColor: 'var(--color-orange-pale)',
		invertedElementsTextColor: 'var(--color-orange)',
	},
	grandlab: {
		bg: 'var(--color-brown)',
		bgContrast: 'var(--color-red)',
		text: WHITE,
		title: 'var(--color-red)',
		invertedElementsBlobColor: 'var(--color-beige-light)',
		invertedElementsTextColor: 'var(--color-red)',
	},
	makerlab: {
		bg: 'var(--color-beige-light)',
		bgContrast: 'var(--color-beige)',
		text: 'var(--color-grey-dark)',
		title: 'var(--color-grey-dark)',
		invertedElementsBlobColor: 'var(--color-beige)',
		invertedElementsTextColor: 'var(--color-grey-dark)',
	},
	factorylab: {
		bg: 'var(--color-teal)',
		bgContrast: 'var(--color-pink)',
		text: WHITE,
		title: 'var(--color-pink)',
		invertedElementsBlobColor: 'var(--color-teal-pale)',
		invertedElementsTextColor: 'var(--color-teal)',
	},
};

/**
 * Resolve the colours a block should use:
 * - `default` fills the card with the theme colour and draws the title in the theme
 *   colour on white;
 * - `inverted` sits on white, drops the decorative shapes, reuses the title colour for
 *   the body text and draws the title in white on the theme colour.
 *
 * `invertedElements*` are passed through from the palette in both variants: they are
 * declared per theme because the element blobs sit on the white card when inverted and
 * cannot be derived from the filled variant.
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
			invertedElementsBlobColor: palette.invertedElementsBlobColor,
			invertedElementsTextColor: palette.invertedElementsTextColor,
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
		invertedElementsBlobColor: palette.invertedElementsBlobColor,
		invertedElementsTextColor: palette.invertedElementsTextColor,
		accent: onDark ? palette.bg : palette.text,
		onDark,
	};
}
