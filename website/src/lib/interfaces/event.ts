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
 * The archive is filtered by its own search and month only: the agenda's
 * publics filter and search narrow upcoming events alone. `total` counts both,
 * so it drives the pagination. `matchTotal` ignores the search and the month:
 * it is what shows the archive, its search box included, so a search with no
 * result cannot hide its own box. `months` ignores the month only, so the
 * dropdown keeps offering the months the selection excludes, but follows the
 * search.
 */
export interface PastEventsList extends PaginatedList<AgendaEventCard> {
	/** Every past event, whatever the search and the month. */
	readonly matchTotal: number;
	/** Month keys (`YYYY-MM`) of the search matches, most recent first; unlabelled. */
	readonly months: string[];
}

export interface EventsPage {
	readonly template: 'events';
	readonly title: string;
	readonly slug: string;
	readonly path: string;
	/** HTML, shown when the agenda search matches no upcoming event. */
	readonly noResultsText: string;
	/** HTML, shown when there is no upcoming event at all. */
	readonly noUpcomingText: string;
	/** HTML, shown when the archive search matches no past event. */
	readonly noPastResultsText: string;
	readonly programs: TaxonomyFilterTerm[];
	readonly publics: TaxonomyFilterTerm[];
	/** Public term slugs carried by at least one upcoming event: the filter never reaches the archive. */
	readonly usedPublics: string[];
	readonly upcomingEvents: AgendaEventCard[];
	readonly pastEvents: PastEventsList;
	readonly body: Block[];
	readonly seo: Seo;
}
