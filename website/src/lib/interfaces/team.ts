import type { Block, CmsImage, HeaderType, Seo } from './page';

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
	readonly headerType: HeaderType;
	readonly overtitle: string | null;
	readonly introTitle: string;
	readonly intro: string;
	readonly sections: TeamSection[];
	readonly body: Block[];
	readonly seo: Seo;
}
