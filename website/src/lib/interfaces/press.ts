import type { CmsDocument, Seo } from './page';

export interface PressContact {
	readonly name: string;
	readonly role: string | null;
	readonly email: string | null;
	readonly phone: string | null;
}

export interface PressPage {
	readonly template: 'press';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly contactTitle: string | null;
	readonly contactPersons: PressContact[];
	readonly resourcesTitle: string | null;
	readonly resourcesCtaTitle: string | null;
	/** ZIP archive gathering the downloadable press resources. */
	readonly resources: CmsDocument | null;
	readonly seo: Seo;
}
