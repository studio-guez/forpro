import type { Seo } from './page';
import type { TaxonomyTerm } from './taxonomy';

export type { TaxonomyTerm };

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
	readonly faqCategories: TaxonomyTerm[];
	readonly sections: FaqSection[];
	readonly seo: Seo;
}
