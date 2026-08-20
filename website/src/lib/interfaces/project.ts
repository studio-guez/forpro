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
