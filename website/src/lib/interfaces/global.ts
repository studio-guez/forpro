import type { CmsImage } from '$lib/interfaces/page';

export interface MenuLink {
	readonly label: string;
	readonly url: string | null;
	/** Set by the CMS from the link type the editor picked; never inferred from the URL. */
	readonly target: '_blank' | null;
}

export interface SecondaryMenuLink extends MenuLink {
	readonly level: 1 | 2;
}

export interface SecondaryMenuGroup {
	readonly title: string | null;
	readonly links: SecondaryMenuLink[];
}

export interface SecondaryMenuColumn {
	readonly title: string | null;
	readonly groups: SecondaryMenuGroup[];
}

export type SocialPlatform =
	| 'facebook'
	| 'instagram'
	| 'linkedin'
	| 'youtube'
	| 'tiktok'
	| 'snapchat'
	| 'x';

export interface SocialLink {
	readonly platform: SocialPlatform;
	readonly url: string;
}

export interface ExternalLink {
	readonly label: string;
	readonly url: string;
}

export interface Header {
	readonly siteTitle: string;
	readonly logo: CmsImage;
	readonly mainMenu: MenuLink[];
	readonly secondaryMenu: SecondaryMenuColumn[];
	readonly externalLinksTitle: string | null;
	readonly externalLinks: ExternalLink[];
	readonly socialLinks: SocialLink[];
}

export interface BannerAnnouncement {
	readonly title: string;
	readonly description: string | null;
	readonly url: string | null;
	readonly target: '_blank' | null;
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
	readonly banner: BannerAnnouncement[];
	readonly favicon: Favicon | null;
}
