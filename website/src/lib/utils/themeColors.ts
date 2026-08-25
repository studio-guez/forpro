import type { Theme, Variant } from '$lib/interfaces/page';

export type ThemeColors = { bg: string; bgContrast: string; text: string; title: string };

/** Card background / decoration / text colours per page theme and block variant. */
export const cardThemeColors: Record<Theme, Record<Variant, ThemeColors>> = {
	default: {
		default: {
			bg: 'var(--color-blue)',
			bgContrast: 'var(--color-blue-light)',
			text: 'var(--color-white)',
			title: 'var(--color-blue)',
		},
		inverted: {
			bg: 'var(--color-white)',
			bgContrast: 'var(--color-blue)',
			text: 'var(--color-grey-dark)',
			title: 'var(--color-blue)',
		},
	},
	campus: {
		default: {
			bg: 'var(--color-blue)',
			bgContrast: 'var(--color-blue-light)',
			text: 'var(--color-white)',
			title: 'var(--color-white)',
		},
		inverted: {
			bg: 'var(--color-white)',
			bgContrast: 'var(--color-blue)',
			text: 'var(--color-blue)',
			title: 'var(--color-blue)',
		},
	},
	entreprendre: {
		default: {
			bg: 'var(--color-purple-light)',
			bgContrast: 'var(--color-purple-pale)',
			text: 'var(--color-white)',
			title: 'var(--color-purple-light)',
		},
		inverted: {
			bg: 'var(--color-purple-pale)',
			bgContrast: 'var(--color-purple-light)',
			text: 'var(--color-white)',
			title: 'var(--color-purple-pale)',
		},
	},
	projets_jeunes: {
		default: {
			bg: 'var(--color-orange)',
			bgContrast: 'var(--color-orange-light)',
			text: 'var(--color-white)',
			title: 'var(--color-white)',
		},
		inverted: {
			bg: 'var(--color-orange-light)',
			bgContrast: 'var(--color-orange)',
			text: 'var(--color-orange)',
			title: 'var(--color-orange)',
		},
	},
	tremplin_jobs: {
		default: {
			bg: 'var(--color-purple-light)',
			bgContrast: 'var(--color-green)',
			text: 'var(--color-white)',
			title: 'var(--color-white)',
		},
		inverted: {
			bg: 'var(--color-purple)',
			bgContrast: 'var(--color-purple-light)',
			text: 'var(--color-purple-light)',
			title: 'var(--color-purple-light)',
		},
	},
	soutiens: {
		default: {
			bg: 'var(--color-blue-light)',
			bgContrast: 'var(--color-pink)',
			text: 'var(--color-white)',
			title: 'var(--color-pink)',
		},
		inverted: {
			bg: 'var(--color-purple)',
			bgContrast: 'var(--color-pink)',
			text: 'var(--color-pink)',
			title: 'var(--color-pink)',
		},
	},
	cekale: {
		default: {
			bg: 'var(--color-purple-pale)',
			bgContrast: 'var(--color-purple)',
			text: 'var(--color-white)',
			title: 'var(--color-purple)',
		},
		inverted: {
			bg: 'var(--color-purple-pale)',
			bgContrast: 'var(--color-purple)',
			text: 'var(--color-purple)',
			title: 'var(--color-purple)',
		},
	},
	la_ref: {
		default: {
			bg: 'var(--color-purple-pale)',
			bgContrast: 'var(--color-pink)',
			text: 'var(--color-white)',
			title: 'var(--color-pink)',
		},
		inverted: {
			bg: 'var(--color-purple)',
			bgContrast: 'var(--color-pink)',
			text: 'var(--color-pink)',
			title: 'var(--color-pink)',
		},
	},
	learninglab: {
		default: {
			bg: 'var(--color-teal)',
			bgContrast: 'var(--color-teal-light)',
			text: 'var(--color-white)',
			title: 'var(--color-white)',
		},
		inverted: {
			bg: 'var(--color-teal-light)',
			bgContrast: 'var(--color-teal)',
			text: 'var(--color-teal)',
			title: 'var(--color-teal)',
		},
	},
	foodlab: {
		default: {
			bg: 'var(--color-orange-pale)',
			bgContrast: 'var(--color-orange-light)',
			text: 'var(--color-orange)',
			title: 'var(--color-orange)',
		},
		inverted: {
			bg: 'var(--color-white)',
			bgContrast: 'var(--color-white)',
			text: 'var(--color-orange)',
			title: 'var(--color-orange)',
		},
	},
	grandlab: {
		default: {
			bg: 'var(--color-brown)',
			bgContrast: 'var(--color-red)',
			text: 'var(--color-white)',
			title: 'var(--color-red)',
		},
		inverted: {
			bg: 'var(--color-orange-light)',
			bgContrast: 'var(--color-red)',
			text: 'var(--color-red)',
			title: 'var(--color-red)',
		},
	},
	makerlab: {
		default: {
			bg: 'var(--color-beige-light)',
			bgContrast: 'var(--color-beige)',
			text: 'var(--color-grey-dark)',
			title: 'var(--color-grey-dark)',
		},
		inverted: {
			bg: 'var(--color-grey-light)',
			bgContrast: 'var(--color-grey-dark)',
			text: 'var(--color-grey-dark)',
			title: 'var(--color-grey-dark)',
		},
	},
};
