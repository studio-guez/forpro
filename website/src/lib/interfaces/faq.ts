import type { Seo } from './page';

export interface TaxonomyTerm {
	readonly slug: string;
	readonly title: string;
	readonly color: string | null;
}

export interface FaqItem {
	readonly question: string;
	readonly answer: string;
	readonly faqCategories: TaxonomyTerm[];
}

export interface FaqSection {
	readonly title: string;
	readonly faqs: FaqItem[];
}

export interface FaqPage {
	readonly template: 'faq';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly sections: FaqSection[];
	readonly seo: Seo;
}
