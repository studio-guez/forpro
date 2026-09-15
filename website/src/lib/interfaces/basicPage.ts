import type { ContentBlock } from './eventProject';
import type { Seo } from './page';

export interface BasicPage {
	readonly template: 'basic-page';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly blocks: ContentBlock[];
	readonly seo: Seo;
}
