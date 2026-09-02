import Lenis from 'lenis';

/** Height of the fixed `SiteHeader` (`main` sits at `pt-27` = 6.75rem), so anchors land under it. */
const HEADER_OFFSET = 108;

let lenis: Lenis | null = null;

/**
 * Boots the smooth scroller on the window scroller.
 * Returns its teardown, so it can be returned straight from `onMount`.
 */
export function initSmoothScroll(): () => void {
	lenis?.destroy();
	lenis = new Lenis({
		autoRaf: true,
		// CMS rich text can link to an in-page `#id`: let Lenis animate those too
		anchors: { offset: -HEADER_OFFSET },
		// nested scrollers (search results, carousels) keep their native scroll
		allowNestedScroll: true
	});

	return () => {
		lenis?.destroy();
		lenis = null;
	};
}

/** Pauses the scroller while something else owns the page scroll (e.g. an open modal). */
export function pauseSmoothScroll(): void {
	lenis?.stop();
}

/** Resumes the scroller after a `pauseSmoothScroll()`. */
export function resumeSmoothScroll(): void {
	lenis?.start();
}
