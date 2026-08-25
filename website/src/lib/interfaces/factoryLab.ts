import type {
	Block,
	CmsImage,
	HeaderType,
	PageCta,
	PageLayout,
	PageParent,
	Seo,
	Theme,
	Variant
} from './page';

export interface CompanyBadge {
	readonly label: string;
	readonly url: string | null;
}

export interface Company {
	readonly image: CmsImage | null;
	readonly title: string;
	readonly url: string | null;
	readonly description: string | null;
	readonly trainings: CompanyBadge[];
	readonly availability: CompanyBadge[];
	readonly followUps: string[];
}

export interface CompaniesModule {
	readonly title: string;
	readonly intro: string | null;
	readonly variant: Variant;
	readonly labels: {
		readonly trainings: string;
		readonly availability: string;
		readonly followUp: string;
	};
	readonly companies: Company[];
}

export interface FactoryLabPage {
	readonly template: 'factory-lab';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly overtitle: string | null;
	readonly theme: Theme;
	readonly headerType: HeaderType;
	readonly cover: CmsImage | null;
	readonly introTitle: string;
	readonly intro: string;
	readonly introLayout: PageLayout;
	readonly introTitleImage: CmsImage | null;
	readonly introCta: PageCta | null;
	readonly companiesModule: CompaniesModule;
	readonly body: Block[];
	readonly seo: Seo;
	readonly trackWithMatomo: boolean;
	readonly parentPage: PageParent | null;
}
