// Date helpers shared by the event pages, cards and listings.

const dayFormat = new Intl.DateTimeFormat('fr-CH', {
	weekday: 'long',
	day: 'numeric',
	month: 'long'
});

const shortDateFormat = new Intl.DateTimeFormat('fr-CH', {
	day: '2-digit',
	month: '2-digit',
	year: 'numeric'
});

const monthFormat = new Intl.DateTimeFormat('fr-CH', { month: 'long', year: 'numeric' });

/** Builds a Date from a `YYYY-MM-DD` date + optional `HH:mm` time (local). */
export const toDate = (date: string | null, time: string | null = null): Date | null => {
	if (!date) return null;
	const dt = new Date(`${date}T${time ?? '00:00'}`);
	return Number.isNaN(dt.getTime()) ? null : dt;
};

/**
 * Value for a time-only `<time datetime>` attribute, e.g. "09:05". The CMS field is free
 * text, so anything that is not `H:mm` yields `undefined` and the attribute is dropped.
 */
export const timeAttr = (time: string | null): string | undefined => {
	const match = time?.match(/^(\d{1,2}):(\d{2})$/);
	if (!match) return undefined;
	const [, hours, minutes] = match;
	if (Number(hours) > 23 || Number(minutes) > 59) return undefined;
	return `${hours.padStart(2, '0')}:${minutes}`;
};

/** "mercredi 12 août" */
export const formatEventDay = (date: Date): string => dayFormat.format(date);

/** "26.07.2026" */
export const formatShortDate = (date: Date): string => shortDateFormat.format(date).replace(/\//g, '.');

/** "août 2026" */
export const formatMonth = (date: Date): string => monthFormat.format(date);

/** Sortable month key of a `YYYY-MM-DD` date, e.g. "2026-07". */
export const monthKey = (date: string | null): string | null => (date ? date.slice(0, 7) : null);
