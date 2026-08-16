import type { CmsImage } from '$lib/interfaces/page';

export interface ExternalLink {
	readonly label: string;
	readonly url: string;
}

export interface Header {
	readonly siteTitle: string;
	readonly logo: CmsImage | null;
}

export interface FaviconPng {
	readonly size: number;
	readonly url: string;
}

export interface FaviconVariant {
	readonly svg: string | null;
	readonly png: FaviconPng[];
}

export interface Favicon {
	readonly light: FaviconVariant;
	readonly dark: FaviconVariant;
}

export interface Global {
	readonly header: Header;
	readonly favicon: Favicon | null;
}
