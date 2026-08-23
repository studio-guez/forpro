import type { Block, CmsImage, Seo } from './page';

export type TeamMemberStatus = 'apprenti' | 'employe';

export interface TeamMember {
	readonly name: string;
	readonly role: string | null;
	readonly status: TeamMemberStatus | null;
	readonly linkedin: string | null;
}

export interface TeamSection {
	readonly title: string;
	readonly members: TeamMember[];
}

export interface TeamPage {
	readonly template: 'team';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly cover: CmsImage | null;
	readonly shortDesc: string;
	readonly sections: TeamSection[];
	readonly body: Block[];
	readonly seo: Seo;
}
