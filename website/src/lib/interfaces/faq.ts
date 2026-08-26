import type { Block, Seo } from './page';
import type { TaxonomyFilterTerm, TaxonomyTerm } from './taxonomy';

export type { TaxonomyTerm };

export interface FaqItem {
	readonly question: string;
	readonly answer: string;
	readonly sectors: TaxonomyTerm[];
	readonly programs: TaxonomyTerm[];
	readonly publics: TaxonomyTerm[];
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
	readonly sectors: TaxonomyFilterTerm[];
	readonly programs: TaxonomyFilterTerm[];
	readonly publics: TaxonomyFilterTerm[];
	readonly sections: FaqSection[];
	readonly body: Block[];
	readonly seo: Seo;
}
