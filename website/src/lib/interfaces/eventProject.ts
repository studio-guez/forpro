import type { CmsImage, CmsMedia, Seo } from './page';

// A YouTube embed resolved by the CMS. `type` distinguishes a regular 16:9
// video from a vertical Short; `embedUrl` is the privacy-friendly nocookie URL.
export interface YoutubeEmbed {
	readonly id: string;
	readonly type: 'video' | 'short';
	readonly url: string;
	readonly embedUrl: string;
}

// A repeatable title + rich-text block shared by events and projects.
export interface ContentBlock {
	readonly title: string;
	readonly description: string;
}

// An external link (title + URL) shared by events and projects.
export interface ContentExternalLink {
	readonly title: string;
	readonly url: string;
}

// Fields shared by every event and project page (pages/event-project-base.yml).
export interface EventProjectBase {
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly subtitle: string;
	readonly shortDesc: string;
	readonly cover: CmsImage | null;
	readonly medias: CmsMedia[];
	readonly embedVideos: YoutubeEmbed[];
	readonly blocks: ContentBlock[];
	readonly externalLinks: ContentExternalLink[];
	readonly seo: Seo;
}
