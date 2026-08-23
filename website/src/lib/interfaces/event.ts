import type { AgendaEventCard, Block, Seo } from './page';
import type { EventProjectBase } from './eventProject';
import type { TaxonomyTerm } from './taxonomy';

export interface EventPage extends EventProjectBase {
	readonly template: 'event';
	// ISO date `YYYY-MM-DD`; only dateStart is required. Times are `HH:mm` strings.
	readonly dateStart: string | null;
	readonly dateEnd: string | null;
	readonly timeStart: string | null;
	readonly timeEnd: string | null;
	readonly domains: TaxonomyTerm[];
	readonly eventThemes: TaxonomyTerm[];
}

// The events index page (events.json.php): every event, split by date.
export interface EventsPage {
	readonly template: 'events';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly domains: TaxonomyTerm[];
	readonly eventThemes: TaxonomyTerm[];
	readonly upcomingEvents: AgendaEventCard[];
	readonly pastEvents: AgendaEventCard[];
	readonly body: Block[];
	readonly seo: Seo;
}
