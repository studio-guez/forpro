// Date helpers shared by the event pages, cards and listings.

const longDateFormat = new Intl.DateTimeFormat('fr-CH', {
	weekday: 'long',
	day: 'numeric',
	month: 'long',
	year: 'numeric'
});

const rangeDateFormat = new Intl.DateTimeFormat('fr-CH', {
	weekday: 'short',
	day: 'numeric',
	month: 'long'
});

const rangeDateWithYearFormat = new Intl.DateTimeFormat('fr-CH', {
	weekday: 'short',
	day: 'numeric',
	month: 'long',
	year: 'numeric'
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

/**
 * Intl emits French dates lowercase, and separates the weekday with a comma as soon as the
 * year is asked for ("lundi, 26 juillet 2027"). The design wants neither.
 */
const dateLabel = (format: Intl.DateTimeFormat, date: Date): string =>
	format
		.format(date)
		.replace(/,/g, '')
		.replace(
			/(^|\s)(\p{L})/gu,
			(_, separator: string, letter: string) => separator + letter.toUpperCase()
		);

/** "Jeudi 26 Juillet 2027" */
export const formatEventDate = (date: Date): string => dateLabel(longDateFormat, date);

/**
 * The two ends of a date range, e.g. `["Mer. 12 Août", "Ven. 14 Septembre"]`, to render as
 * "Du … au …". The year is dropped unless the range straddles two of them.
 */
export const formatEventDateRange = (start: Date, end: Date): [string, string] => {
	const format =
		start.getFullYear() === end.getFullYear() ? rangeDateFormat : rangeDateWithYearFormat;
	return [dateLabel(format, start), dateLabel(format, end)];
};

/** "13h00". The CMS field is free text, so anything that is not `H:mm` is passed through. */
export const formatEventTime = (time: string | null): string | null => {
	if (!time) return null;
	return timeAttr(time)?.replace(':', 'h') ?? time;
};

/** "26.07.2026" */
export const formatShortDate = (date: Date): string =>
	shortDateFormat.format(date).replace(/\//g, '.');

/** "Août 2026" */
export const formatMonth = (date: Date): string => dateLabel(monthFormat, date);

/** Sortable month key of a `YYYY-MM-DD` date, e.g. "2026-07". */
export const monthKey = (date: string | null): string | null => (date ? date.slice(0, 7) : null);

/** Same key, from a `Date` — local time, so it never slips a month on a timezone offset. */
export const monthKeyOf = (date: Date): string =>
	`${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;

/**
 * Every month key from `start` to `end`, both included, e.g.
 * `["2026-09", "2026-10"]`. Empty when either end is unparseable or the range
 * runs backwards.
 */
export const monthKeysBetween = (start: string, end: string): string[] => {
	const month = toDate(`${start}-01`);
	const last = toDate(`${end}-01`);
	if (!month || !last) return [];

	const keys: string[] = [];
	while (month <= last) {
		keys.push(monthKeyOf(month));
		month.setMonth(month.getMonth() + 1);
	}
	return keys;
};

/** "Août 2026" from a month key. */
export const monthKeyLabel = (key: string): string => {
	const date = toDate(`${key}-01`);
	return date ? formatMonth(date) : key;
};
