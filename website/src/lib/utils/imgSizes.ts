/**
 * `sizes` builders for `Img.svelte`.
 *
 * Every section is capped at `--content-max`, so a plain `100vw` lies on wide screens:
 * the browser then picks the 2560 / 3840 candidate of the `default` srcset ladder for a
 * slot that never exceeds ~1128px. These helpers describe the slot the image really gets.
 *
 * The `90rem` below mirrors `--content-max` in `app.css` — a `sizes` attribute cannot read
 * a CSS variable, so the two are kept in sync by hand.
 */

/** Rendered slot width per breakpoint: `{ <min viewport width in px>: <css width> }`. */
export type Steps = Readonly<Record<number, string>>;

/** Viewport width, capped at the content column. */
const PAGE_WIDTH = 'min(100vw, 90rem)';

/** A section that spans the content column edge to edge (a `main > *` with no gutter). */
export const PAGE: Steps = { 0: PAGE_WIDTH };

/** A `px-card` section: page gutter of 1.25rem / 3.75rem (`lg`) / 7.5rem (`xl`). */
export const PAGE_CARD: Steps = {
	0: 'calc(100vw - 2.5rem)',
	1024: 'calc(100vw - 7.5rem)',
	1280: `calc(${PAGE_WIDTH} - 15rem)`
};

/** The content box of a `Card` (`lg:px-9` section around a `px-card` box). */
export const CARD: Steps = {
	0: 'calc(100vw - 2.5rem)',
	1024: 'calc(100vw - 12rem)',
	1280: `calc(${PAGE_WIDTH} - 19.5rem)`
};

/** The full plate of a `CardSmall` (`lg:px-9` section, no inner gutter to subtract). */
export const CARD_SMALL: Steps = {
	0: '100vw',
	1024: `calc(${PAGE_WIDTH} - 4.5rem)`
};

/** Serialises steps into a `sizes` value: largest breakpoint first, bare default last. */
export const toSizes = (steps: Steps): string =>
	Object.keys(steps)
		.map(Number)
		.sort((a, b) => b - a)
		.map((min) => (min === 0 ? steps[min] : `(min-width: ${min}px) ${steps[min]}`))
		.join(', ');

/** Value of the entry that applies at `breakpoint` (the largest key at or below it). */
const at = <T>(steps: Readonly<Record<number, T>>, breakpoint: number): T =>
	steps[
		Object.keys(steps)
			.map(Number)
			.filter((min) => min <= breakpoint)
			.reduce((a, b) => Math.max(a, b), 0)
	];

/**
 * Narrows a container to one grid cell. `columns` maps a breakpoint to the column count
 * there (a fractional count expresses a peeking carousel slide: `w-4/5` is `1.25`), `gap`
 * is the column gap in rem, `span` how many columns the cell covers where they exist.
 */
export const cell = (
	container: Steps,
	columns: Readonly<Record<number, number>>,
	gap: number,
	span = 1
): Steps =>
	Object.fromEntries(
		[...new Set([...Object.keys(container), ...Object.keys(columns)])]
			.map(Number)
			.sort((a, b) => a - b)
			.map((breakpoint) => {
				const width = at(container, breakpoint);
				const count = at(columns, breakpoint);
				if (count === 1) return [breakpoint, width];
				const spanned = Math.min(span, count);
				const gutters = Number.isInteger(count) ? `${(count - 1) * gap}rem` : null;
				const track = `(${width}${gutters ? ` - ${gutters}` : ''}) / ${count}`;
				return [
					breakpoint,
					spanned === 1
						? `calc(${track})`
						: `calc(${track} * ${spanned} + ${(spanned - 1) * gap}rem)`
				];
			})
	);
