import type { CmsImage, PageCta, Seo } from './page';

export interface InfosPratiquesPage {
	readonly template: 'infos-pratiques';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly openingHoursTitle: string | null;
	/** Rich text (writer). */
	readonly openingHoursContent: string | null;
	readonly foodlabOpeningHoursTitle: string | null;
	readonly foodlabImage: CmsImage | null;
	readonly foodlabCta: PageCta | null;
	readonly accessTitle: string | null;
	/** Rich text (writer). */
	readonly accessContent: string | null;
	/** `src` of a Google Maps embed iframe, already vetted by the CMS; null hides the map. */
	readonly mapEmbedUrl: string | null;
	readonly seo: Seo;
}
