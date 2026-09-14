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
	/** Venue; null when the event is held at the foundation's own address. */
	readonly location: string | null;
	readonly programs: TaxonomyTerm[];
	readonly publics: TaxonomyTerm[];
}

/**
 * One page of the past-events archive, from `/api/list/past-events` or embedded
 * unfiltered in the events page payload.
 *
 * The archive is filtered by publics, its own search and month: the agenda's
 * other search answers across upcoming events alone. `total` counts all three,
 * so it drives the pagination. `matchTotal` ignores the search and the month:
 * it is what shows the archive, its search box included, so a search with no
 * result cannot hide its own box. `months` ignores the month only, so the
 * dropdown keeps offering the months the selection excludes, but follows the
 * search.
 */
export interface PastEventsList extends PaginatedList<AgendaEventCard> {
	/** Events matching the publics, whatever the search and the month. */
	readonly matchTotal: number;
	/** Month keys (`YYYY-MM`) of the search matches, most recent first; unlabelled. */
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
