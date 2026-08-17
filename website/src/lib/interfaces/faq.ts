import type { Seo } from './page';

export interface TaxonomyTerm {
	readonly slug: string;
	readonly title: string;
	readonly color: string | null;
}

export interface FaqItem {
	readonly question: string;
	readonly answer: string;
	readonly domains: TaxonomyTerm[];
}

export interface FaqSection {
	readonly title: string;
	readonly faqs: FaqItem[];
}

export interface FaqPage {
	readonly title: string;
	readonly slug: string;
	readonly sections: FaqSection[];
	readonly seo: Seo;
}
