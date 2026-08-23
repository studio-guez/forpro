import type { Block, CmsImage, Seo } from './page';

export interface JobOffersPage {
	readonly template: 'job-offers';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly cover: CmsImage | null;
	readonly shortDesc: string;
	readonly body: Block[];
	readonly seo: Seo;
}
