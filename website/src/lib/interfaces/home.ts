import type { CmsDocument, CmsImage, PageCta, Seo } from './page';

/** One of the 3 tilted cards under the welcome text; the colours are fixed by slot. */
export interface HomeWelcomeCard {
	readonly title: string;
	readonly cta: PageCta | null;
}

/**
 * A card of the resources carousel. The CMS draws 3 `page` cards from the
 * editor's list and 2 `project` cards on every request, and shuffles them.
 */
export interface HomeResourceCard {
	readonly type: 'page' | 'project';
	readonly overtitle: string | null;
	readonly title: string;
	/** Rich text, shown under the carousel for the active card. */
	readonly shortDesc: string | null;
	readonly cover: CmsImage | null;
	readonly url: string;
}

export interface HomeLottie extends CmsDocument {
	/** Text alternative for screen readers; null when the animation is decorative. */
	readonly alt: string | null;
	/** Still image shown when the animation cannot be played; null until the editor uploads one. */
	readonly poster: CmsImage | null;
}

export interface HomePage {
	readonly template: 'home';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	/** `.json` or `.lottie` file, served as-is; null until the editor uploads one. */
	readonly lottie: HomeLottie | null;
	/** Small-screen variant; null means `lottie` is shown on every viewport. */
	readonly lottieMobile: HomeLottie | null;
	readonly welcomeTitle: string;
	readonly welcomeShortDesc: string;
	/** Always 3 entries (the blueprint has one title + CTA pair per card). */
	readonly welcomeCards: HomeWelcomeCard[];
	readonly resourcesTitle: string;
	readonly resources: HomeResourceCard[];
	readonly seo: Seo;
}
