import type { EventProjectBase } from './eventProject';
import type { TaxonomyTerm } from './taxonomy';

export interface EventPage extends EventProjectBase {
	readonly template: 'event';
	// ISO-like local datetime (`YYYY-MM-DDTHH:mm`); `dateEnd` is optional.
	readonly dateStart: string | null;
	readonly dateEnd: string | null;
	readonly domains: TaxonomyTerm[];
	readonly eventThemes: TaxonomyTerm[];
}
