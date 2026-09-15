import type { Block, CmsImage, HeaderType, Seo } from './page';

export type TeamMemberStatus = 'other' | 'apprentice' | 'teacher';

export interface TeamMember {
	readonly name: string;
	readonly role: string | null;
	readonly status: TeamMemberStatus | null;
	readonly linkedin: string | null;
}

/** A labelled group of members inside a section (a pôle, a team...). */
export interface TeamGroup {
	readonly title: string | null;
	readonly members: TeamMember[];
}

/** An expandable section of the team page, holding one or more groups. */
export interface TeamSection {
	readonly title: string;
	readonly groups: TeamGroup[];
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
