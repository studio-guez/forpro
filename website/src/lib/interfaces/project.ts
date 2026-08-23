import type { Block, ProjetCard, Seo } from './page';
import type { EventProjectBase } from './eventProject';
import type { TaxonomyTerm } from './taxonomy';

export interface CollectiveMember {
	readonly name: string;
}

export interface ProjectPage extends EventProjectBase {
	readonly template: 'project';
	readonly projectThemes: TaxonomyTerm[];
	readonly projectTypes: TaxonomyTerm[];
	readonly collectiveName: string | null;
	readonly collectiveMembers: CollectiveMember[];
}

// The projects index page (projects.json.php): every project plus its filters.
export interface ProjectsPage {
	readonly template: 'projects';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly projectThemes: TaxonomyTerm[];
	readonly projectTypes: TaxonomyTerm[];
	/** Years used by at least one project, most recent first. Filter only. */
	readonly years: number[];
	readonly projects: ProjetCard[];
	readonly body: Block[];
	readonly seo: Seo;
}
