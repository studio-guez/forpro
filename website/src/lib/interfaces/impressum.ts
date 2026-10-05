import type { Block, CmsImage, Seo } from './page';

export interface ImpressumLink {
	readonly label: string;
	readonly url: string;
}

export interface ImpressumPartner {
	readonly image: CmsImage | null;
	readonly title: string;
	readonly link: ImpressumLink | null;
}

export interface ImpressumCredit {
	readonly role: string;
	readonly names: string[];
	readonly link: ImpressumLink | null;
}

export interface ImpressumSection {
	readonly title: string;
	readonly credits: ImpressumCredit[];
}

export interface ImpressumPage {
	readonly template: 'impressum';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly partnersTitle: string | null;
	readonly partners: ImpressumPartner[];
	readonly sections: ImpressumSection[];
	readonly body: Block[];
	readonly seo: Seo;
}
