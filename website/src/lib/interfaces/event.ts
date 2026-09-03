import type { AgendaEventCard, Block, Seo } from './page';
import type { EventProjectBase } from './eventProject';
import type { PaginatedList } from './pagination';
import type { TaxonomyFilterTerm, TaxonomyTerm } from './taxonomy';

export interface EventPage extends EventProjectBase {
	readonly template: 'event';
	// ISO date `YYYY-MM-DD`; only dateStart is required. Times are `HH:mm` strings.
	readonly dateStart: string | null;
	readonly dateEnd: string | null;
	readonly timeStart: string | null;
	readonly timeEnd: string | null;
	readonly programs: TaxonomyTerm[];
	readonly publics: TaxonomyTerm[];
}

/**
 * One page of the past-events archive, from `/api/list/past-events` or embedded
 * unfiltered in the events page payload.
 *
 * `total` counts search + publics + month, so it drives the pagination;
 * `matchTotal` and `months` deliberately ignore the month, since the results
 * header counts every match and the dropdown has to keep offering the months
 * the current selection excludes.
 */
export interface PastEventsList extends PaginatedList<AgendaEventCard> {
	/** Events matching search + publics, whatever the month. */
	readonly matchTotal: number;
	/** Month keys (`YYYY-MM`) of the matches, most recent first; unlabelled. */
	readonly months: string[];
}

// The events index page (events.json.php): every upcoming event, and the first
// page of the paginated past archive.
export interface EventsPage {
	readonly template: 'events';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	readonly programs: TaxonomyFilterTerm[];
	readonly publics: TaxonomyFilterTerm[];
	/** Public term slugs carried by at least one event, past or upcoming. */
	readonly usedPublics: string[];
	readonly upcomingEvents: AgendaEventCard[];
	readonly pastEvents: PastEventsList;
	readonly body: Block[];
	readonly seo: Seo;
}
