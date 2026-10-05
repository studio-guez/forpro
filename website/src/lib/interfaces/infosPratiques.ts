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
	/** Static picture of the access map; it opens `mapUrl` when one is set. */
	readonly mapImage: CmsImage | null;
	/** Public Google Maps link ("Share → Copy link"); null hides the link. */
	readonly mapUrl: string | null;
	readonly seo: Seo;
}
