import type { Block, ProjetCard, Seo } from './page';
import type { EventProjectBase } from './eventProject';
import type { PaginatedList } from './pagination';
import type { TaxonomyFilterTerm, TaxonomyTerm } from './taxonomy';

export interface CollectiveMember {
	readonly name: string;
}

export interface ProjectPage extends EventProjectBase {
	readonly template: 'project';
	readonly programs: TaxonomyTerm[];
	readonly resourcesTaxonomy: TaxonomyTerm[];
	readonly categories: TaxonomyTerm[];
	readonly collectiveName: string | null;
	readonly collectiveMembers: CollectiveMember[];
}

/** One page of the projects archive, from `/api/list/projects`. */
export type ProjectsList = PaginatedList<ProjetCard>;

export interface ProjectsPage {
	readonly template: 'projects';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	/** HTML, shown when the search or the filters match no project. */
	readonly noResultsText: string;
	readonly resourcesTaxonomy: TaxonomyFilterTerm[];
	/** Resource term slugs carried by at least one project. */
	readonly usedResourcesTaxonomy: string[];
	readonly programs: TaxonomyFilterTerm[];
	/** Program term slugs carried by at least one project. */
	readonly usedPrograms: string[];
	readonly categories: TaxonomyFilterTerm[];
	/** Category term slugs carried by at least one project. */
	readonly usedCategories: string[];
	/** Years used by at least one project, most recent first. Filter only. */
	readonly years: number[];
	readonly projects: ProjectsList;
	readonly body: Block[];
	readonly seo: Seo;
}
