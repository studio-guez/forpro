export interface CmsImage {
	readonly focus: string | null;
	readonly caption: string | null;
	readonly alt: string | null;
	readonly link: string | null;
	readonly photoCredit: string | null;
	readonly url: string;
	readonly srcset: string;
	readonly width: number;
	readonly height: number;
}

export interface Block {
	readonly id: string;
	readonly type: string;
	readonly isHidden: boolean;
	readonly content: Record<string, unknown>;
}

export interface Seo {
	readonly title: string;
	readonly description: string;
	readonly canonicalUrl: string;
	readonly robots: string;
	readonly locale: string;
	readonly ogTitle: string;
	readonly ogDescription: string;
	readonly ogSiteName: string;
	readonly ogType: string;
	readonly ogImage: string | null;
	readonly twitterCardType: string;
	readonly twitterSite: string;
	readonly twitterCreator: string;
	readonly schemas: Record<string, unknown>[];
}

export type ArrowColor = 'white' | 'blue' | 'orange' | 'pink' | 'purple';

export interface Page {
	readonly title: string;
	readonly slug: string;
	readonly overtitle: string | null;
	readonly titleHasArrow: boolean;
	readonly arrowColor: ArrowColor | '' | null;
	readonly cover: CmsImage | null;
	readonly body: Block[];
	readonly seo: Seo;
	readonly trackWithMatomo: boolean;
}
