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
	'facebook' | 'instagram' | 'linkedin' | 'youtube' | 'tiktok' | 'snapchat' | 'x';

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

export interface FooterAddress {
	readonly name: string | null;
	readonly street: string | null;
	readonly postalCode: string | null;
	readonly locality: string | null;
	readonly region: string | null;
	readonly country: string | null;
	readonly mapUrl: string | null;
}

export interface Footer {
	/** The same file as `Header.logo`; the CMS serialises it into both payloads. */
	readonly logo: CmsImage;
	readonly logoEntrepriseFormatrice: CmsImage | null;
	readonly address: FooterAddress;
	readonly email: string | null;
	/** Human-readable phone number, as typed in the Panel. */
	readonly phone: string | null;
	/** The same number normalised into a `tel:` URI by the CMS. */
	readonly phoneUrl: string | null;
	readonly socialsTitle: string | null;
	readonly socialLinks: SocialLink[];
	readonly menuTitle: string | null;
	readonly menuLinks: MenuLink[];
	readonly newsletterTitle: string | null;
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

export interface CookieSettings {
	readonly text: string | null;
	readonly privacyPolicyUrl: string | null;
}

export interface Global {
	readonly header: Header;
	readonly footer: Footer;
	readonly banner: BannerAnnouncement[];
	readonly favicon: Favicon | null;
	readonly cookies: CookieSettings;
}
