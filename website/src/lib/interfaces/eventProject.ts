import type { Block, CmsImage, CmsMedia, PageParent, Seo, VideoItem } from './page';

export interface ContentBlock {
	readonly title: string;
	readonly description: string;
}

export interface ContentExternalLink {
	readonly title: string;
	readonly url: string;
}

/** `content-medias`: a gallery of uploaded images and/or videos. */
export interface ContentMediasContent {
	readonly medias: CmsMedia[];
}

/** `content-video`: one uploaded file or YouTube embed; null when the row is unusable. */
export interface ContentVideoContent {
	readonly video: VideoItem | null;
}

/** `content-text`: a title + rich-text paragraph. */
export type ContentTextContent = ContentBlock;

/** `content-links`: a titled list of external links; `title` null means "Liens externes". */
export interface ContentLinksContent {
	readonly title: string | null;
	readonly links: ContentExternalLink[];
}

export interface EventProjectBase {
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly subtitle: string;
	readonly shortDesc: string;
	readonly cover: CmsImage | null;
	/** The `fields/contentBody` blockbuilder: medias / video / text / links, freely ordered. */
	readonly body: Block[];
	/** The index the "back" link points to (agenda / projets). */
	readonly parentPage: PageParent | null;
	readonly seo: Seo;
}
