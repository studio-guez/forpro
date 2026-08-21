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
