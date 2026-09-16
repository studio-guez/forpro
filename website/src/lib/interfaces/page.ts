import type { TaxonomyTerm } from '$lib/interfaces/taxonomy';

export interface CmsImage {
	readonly focus: string | null;
	readonly caption: string | null;
	readonly alt: string | null;
	readonly link: string | null;
	readonly photoCredit: string | null;
	readonly url: string;
	readonly srcset: string;
	readonly width: number;
	readonly height: number;
}

export interface Block {
	readonly id: string;
	readonly type: string;
	readonly isHidden: boolean;
	readonly content: Record<string, unknown>;
}

export interface CmsDocument {
	readonly url: string;
	readonly filename: string;
	readonly extension: string;
	/** Human readable file size, e.g. "1.2 MB". */
	readonly size: string;
	readonly mime: string;
}

export interface Seo {
	readonly title: string;
	readonly description: string;
	readonly canonicalUrl: string;
	readonly robots: string;
	readonly locale: string;
	readonly ogTitle: string;
	readonly ogDescription: string;
	readonly ogSiteName: string;
	readonly ogType: string;
	readonly ogImage: string | null;
	readonly twitterCardType: string;
	readonly twitterSite: string;
	readonly twitterCreator: string;
	readonly schemas: Record<string, unknown>[];
	readonly trackWithMatomo: boolean;
}

export type Variant = 'default' | 'inverted';
export type ImagePosition = 'left' | 'right';
export type ImageFit = 'cover' | 'contain';

export interface ModuleTitreTexteImageContent {
	readonly title: string;
	readonly description: string;
	readonly image: CmsImage | null;
	readonly imagePosition: ImagePosition;
	readonly imageFit: ImageFit;
	readonly variant: Variant;
	readonly cta: PageCta | null;
}

export interface ModuleTextContent {
	readonly title: string;
	readonly hideTitle: boolean;
	readonly content: string | null;
	readonly ctas: PageCta[];
	readonly variant: Variant;
}

export interface CmsVideo {
	readonly type: 'video';
	readonly alt: string | null;
	readonly caption: string | null;
	readonly photoCredit: string | null;
	readonly link: string | null;
	readonly url: string;
	readonly mime: string;
}

export type CmsMedia = (CmsImage & { readonly type: 'image' }) | CmsVideo;

export interface YoutubeEmbedData {
	readonly id: string;
	readonly type: 'video' | 'short';
	readonly url: string;
	readonly embedUrl: string;
}

/** One row of the shared `fields/video` structure: an uploaded file or a YouTube embed. */
export type VideoItem =
	| { readonly source: 'upload'; readonly file: CmsVideo }
	| { readonly source: 'youtube'; readonly embed: YoutubeEmbedData };

export type CasesLayout = 'alternate' | 'images-right' | 'images-left';

export interface ModuleCasesRow {
	readonly title: string | null;
	readonly description: string;
	readonly cta: PageCta | null;
	readonly media: CmsMedia[];
}

export interface ModuleCasesContent {
	readonly title: string;
	readonly hideTitle: boolean;
	readonly intro: string;
	readonly rows: ModuleCasesRow[];
	readonly cta: PageCta | null;
	readonly layout: CasesLayout;
	readonly imageFit: ImageFit;
	readonly variant: Variant;
}

export interface GrilleImagesItem {
	readonly image: CmsImage;
	readonly title: string;
}

export interface ModuleGrilleImagesContent {
	readonly title: string;
	readonly shortDesc: string | null;
	/** Always 5 entries (enforced by the blueprint). */
	readonly images: GrilleImagesItem[];
	readonly cta: PageCta | null;
	readonly variant: Variant;
}

export interface ModuleVideoContent {
	readonly title: string;
	readonly shortDesc: string | null;
	readonly video: VideoItem | null;
	readonly contentTitle: string | null;
	readonly content: string | null;
	readonly variant: Variant;
}

/** One of the 3 pairs emitted by the shared `fields/threeElements` structure. */
export interface ThreeElementsItem {
	readonly title: string;
	readonly description: string;
}

export interface Module3ElementsContent {
	readonly title: string;
	readonly shortDesc: string | null;
	/** Empty or exactly 3 entries (enforced by the blueprint). */
	readonly elements: ThreeElementsItem[];
	readonly variant: Variant;
}

export interface InfosPratiquesFaq {
	readonly question: string;
	readonly answer: string;
}

export interface ModuleInfosPratiquesContent {
	readonly title: string;
	readonly subtitle: string;
	readonly elements: ThreeElementsItem[];
	readonly faqs: InfosPratiquesFaq[];
	readonly cta: PageCta | null;
	readonly variant: Variant;
}

export interface TimelineStep {
	readonly title: string;
	readonly shortDesc: string;
}

export interface ModuleTimelineContent {
	readonly title: string;
	readonly hideTitle: boolean;
	readonly steps: TimelineStep[];
}

export interface AgendaEventCard {
	readonly title: string;
	readonly url: string;
	readonly shortDesc: string;
	readonly cover: CmsImage | null;
	/** ISO date `YYYY-MM-DD`. Times are `HH:mm` strings; all optional. */
	readonly dateStart: string | null;
	readonly dateEnd: string | null;
	readonly timeStart: string | null;
	readonly timeEnd: string | null;
	readonly programs: TaxonomyTerm[];
	readonly sectors: TaxonomyTerm[];
	readonly publics: TaxonomyTerm[];
}

export interface ModuleAgendaContent {
	readonly title: string;
	readonly shortDesc: string | null;
	readonly events: AgendaEventCard[];
	readonly cta: PageCta | null;
	readonly variant: Variant;
}

export interface ProjetCard {
	readonly title: string;
	readonly url: string;
	readonly shortDesc: string;
	readonly cover: CmsImage | null;
	readonly collectiveName: string | null;
	/** `YYYY-MM-DD`. Orders every projects listing; its year is the filter on the projects page. */
	readonly date: string;
	readonly programs: TaxonomyTerm[];
	readonly sectors: TaxonomyTerm[];
	readonly categories: TaxonomyTerm[];
}

export interface ModuleProjetsContent {
	readonly title: string;
	readonly shortDesc: string | null;
	readonly projects: ProjetCard[];
	readonly cta: PageCta | null;
	readonly variant: Variant;
}

export interface ResourceCard {
	readonly title: string;
	readonly shortDesc: string;
	readonly image: CmsImage | null;
}

export interface ModuleResourcesContent {
	readonly title: string;
	readonly shortDesc: string | null;
	readonly resources: ResourceCard[];
	readonly variant: Variant;
}

export type CtaVariant = Variant | 'backgroundImage';

export interface ModuleCtaContent {
	readonly title: string;
	readonly subtitle: string | null;
	readonly links: PageCta[];
	readonly variant: CtaVariant;
	readonly backgroundImage: CmsImage | null;
}

export interface PartnerItem {
	readonly logo: CmsImage;
	readonly url: string | null;
	readonly label: string | null;
}

export interface ModulePartenairesContent {
	readonly title: string;
	readonly subtitle: string | null;
	readonly partners: PartnerItem[];
	readonly variant: Variant;
}

export type Theme =
	| 'default'
	| 'campus'
	| 'entreprendre'
	| 'projets_jeunes'
	| 'tremplin_jobs'
	| 'soutiens'
	| 'cekale'
	| 'la_ref'
	| 'learninglab'
	| 'foodlab'
	| 'grandlab'
	| 'makerlab'
	| 'factorylab';

export interface PageParent {
	readonly title: string;
	readonly slug: string;
	readonly path: string;
}

export type CtaIcon = 'plus' | 'email' | 'phone' | 'arrow';

export interface PageCta {
	readonly label: string;
	readonly url: string;
	readonly icon: CtaIcon | null;
	/** Set by the CMS from the link type the editor picked; never inferred from the URL. */
	readonly target: '_blank' | null;
	/** True for CMS `file` CTAs: the anchor downloads the document rather than navigating to it. */
	readonly download?: boolean;
}

export type PageLayout = '1col' | '2col';

/** `full`: hero + intro section. `compact`: single self-contained header. */
export type HeaderType = 'full' | 'compact';

/** Everything `PageHeader` may need; every page type with a header satisfies it. */
export interface PageHeaderData {
	readonly headerType: HeaderType;
	readonly title: string;
	readonly intro: string;
	readonly cover: CmsImage | null;
	readonly overtitle?: string | null;
	readonly introTitle?: string;
	readonly introTitleImage?: CmsImage | null;
	readonly introLayout?: PageLayout;
	readonly introCta?: PageCta | null;
	readonly parentPage?: PageParent | null;
	readonly theme?: Theme;
}

export interface Page {
	readonly template: 'page';
	readonly title: string;
	readonly slug: string;
	readonly overtitle: string | null;
	readonly theme: Theme;
	readonly headerType: HeaderType;
	readonly cover: CmsImage | null;
	readonly introTitle: string;
	readonly intro: string;
	readonly introLayout: PageLayout;
	readonly introTitleImage: CmsImage | null;
	readonly introCta: PageCta | null;
	readonly body: Block[];
	readonly seo: Seo;
	readonly path: string;
	readonly parentPage: PageParent | null;
}
