import type { ContentBlock } from './eventProject';
import type { Seo } from './page';

// A plain editorial page (privacy policy, terms, ...): a title plus the shared
// repeatable title + rich-text blocks, no body blockbuilder.
export interface BasicPage {
	readonly template: 'basic-page';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly blocks: ContentBlock[];
	readonly seo: Seo;
}
