import type { Page } from './page';
import type { BasicPage } from './basicPage';
import type { FaqPage } from './faq';
import type { EventPage, EventsPage } from './event';
import type { ProjectPage, ProjectsPage } from './project';
import type { TeamPage } from './team';
import type { JobOfferPage, JobOffersPage } from './jobOffers';
import type { MissionPage, MissionsPage } from './missions';
import type { ImpressumPage } from './impressum';
import type { PressPage } from './press';
import type { FactoryLabPage } from './factoryLab';
import type { InfosPratiquesPage } from './infosPratiques';
import type { HomePage } from './home';

export type CmsContent =
	| Page
	| BasicPage
	| FaqPage
	| EventPage
	| EventsPage
	| ProjectPage
	| ProjectsPage
	| TeamPage
	| JobOfferPage
	| JobOffersPage
	| MissionPage
	| MissionsPage
	| ImpressumPage
	| PressPage
	| FactoryLabPage
	| InfosPratiquesPage
	| HomePage;
