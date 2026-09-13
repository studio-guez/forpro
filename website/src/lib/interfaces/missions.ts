import type { Block, CmsImage, HeaderType, PageCta, PageParent, Seo, Theme } from './page';
import type { PaginatedList } from './pagination';
import type { TaxonomyFilterTerm, TaxonomyTerm } from './taxonomy';

// Card payload of a mission listed on the missions index.
export interface MissionCard {
	readonly title: string;
	readonly url: string;
	/** Optional free text, shown as is (e.g. "Dès le 15 septembre"). */
	readonly date: string;
	readonly location: string;
	readonly shortDesc: string;
	readonly terms: TaxonomyTerm[];
}

export interface MissionPage {
	readonly template: 'mission';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly parentPage: PageParent | null;
	/** A closed mission keeps its page, but leaves the index and the sitemap. */
	readonly openToApplications: boolean;
	readonly categories: TaxonomyTerm[];
	readonly announcer: string;
	/** Optional free text, shown as is (e.g. "Dès le 15 septembre"). */
	readonly date: string;
	readonly location: string;
	readonly applyCta: PageCta | null;
	readonly cover: CmsImage | null;
	readonly introTitle: string;
	readonly shortDesc: string;
	readonly profile: string;
	readonly tasks: string;
	readonly planning: string;
	readonly seo: Seo;
}

/** One page of the missions list, from `/api/list/missions`. */
export type MissionsList = PaginatedList<MissionCard>;

// The missions index page (missions.json.php): the filters, and the first
// unfiltered page of the paginated list, most recently published first.
export interface MissionsPage {
	readonly template: 'missions';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly overtitle: string | null;
	readonly theme: Theme;
	readonly headerType: HeaderType;
	readonly cover: CmsImage | null;
	readonly introTitle: string;
	readonly intro: string;
	readonly parentPage: PageParent | null;
	readonly categories: TaxonomyFilterTerm[];
	/** Category term slugs carried by at least one mission. */
	readonly usedCategories: string[];
	readonly missions: MissionsList;
	readonly body: Block[];
	readonly seo: Seo;
}
