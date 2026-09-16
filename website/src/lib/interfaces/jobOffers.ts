import type {
	Block,
	CmsDocument,
	CmsImage,
	HeaderType,
	PageParent,
	Seo,
	TimelineStep
} from './page';
import type { TaxonomyTerm } from './taxonomy';

export interface JobOfferQuestion {
	/** Short heading shown next to the text, e.g. "Question 1". */
	readonly question: string;
	readonly answer: string;
}

export interface JobOfferCard {
	readonly title: string;
	readonly url: string;
	/** ISO date `YYYY-MM-DD`. */
	readonly publishedDate: string;
	readonly location: string;
	/** ISO date `YYYY-MM-DD`. */
	readonly deadline: string;
	/** Percentages; the maximum is only set when the rate is a range. */
	readonly activityRateMin: number;
	readonly activityRateMax: number | null;
	readonly terms: TaxonomyTerm[];
}

export interface JobOfferPage {
	readonly template: 'job-offer';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly parentPage: PageParent | null;
	/** A closed offer keeps its page, but leaves the index and the sitemap. */
	readonly openToApplications: boolean;
	readonly sectors: TaxonomyTerm[];
	readonly description: string;
	readonly profile: string;
	readonly conditions: string;
	readonly location: string;
	readonly activityRateMin: number;
	readonly activityRateMax: number | null;
	/** Free text, e.g. "dès que possible". */
	readonly startDate: string;
	/** ISO date `YYYY-MM-DD`. */
	readonly deadline: string;
	/** Null once the offer is closed: the address is not shipped at all then. */
	readonly applicationEmail: string | null;
	readonly pdfOffer: CmsDocument | null;
	readonly applicationContent: string;
	readonly applicationQuestions: JobOfferQuestion[];
	readonly recruitingSteps: TimelineStep[];
	readonly seo: Seo;
}

export interface JobOffersPage {
	readonly template: 'job-offers';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly cover: CmsImage | null;
	readonly headerType: HeaderType;
	readonly overtitle: string | null;
	readonly introTitle: string;
	readonly intro: string;
	/** HTML, shown when no offer is open to applications. */
	readonly noOffersText: string;
	readonly jobOffers: JobOfferCard[];
	readonly body: Block[];
	readonly seo: Seo;
}
