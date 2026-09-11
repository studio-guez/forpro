import type { CmsImage, CmsMedia, PageParent, Seo, VideoItem } from './page';

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
	/** Uploaded files or YouTube embeds, shown below the media gallery. */
	readonly videos: VideoItem[];
	readonly blocks: ContentBlock[];
	readonly externalLinks: ContentExternalLink[];
	/** The index the "back" link points to (agenda / projets). */
	readonly parentPage: PageParent | null;
	readonly seo: Seo;
}
