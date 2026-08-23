import type { Page } from './page';
import type { FaqPage } from './faq';
import type { EventPage, EventsPage } from './event';
import type { ProjectPage, ProjectsPage } from './project';
import type { TeamPage } from './team';
import type { JobOfferPage, JobOffersPage } from './jobOffers';
import type { MissionPage, MissionsPage } from './missions';

// Any CMS content resolved by the [...slug] route, discriminated by `template`.
export type CmsContent =
	| Page
	| FaqPage
	| EventPage
	| EventsPage
	| ProjectPage
	| ProjectsPage
	| TeamPage
	| JobOfferPage
	| JobOffersPage
	| MissionPage
	| MissionsPage;
