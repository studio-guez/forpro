import Lenis from 'lenis';

/** Height of the fixed `SiteHeader` (`main` sits at `pt-27` = 6.75rem), so anchors land under it. */
const HEADER_OFFSET = 108;

let lenis: Lenis | null = null;

/**
 * Boots the smooth scroller on the window scroller.
 * Returns its teardown, so it can be returned straight from `onMount`.
 */
export function initSmoothScroll(): () => void {
	// With `prefers-reduced-motion` the native scroller is kept; `html { scroll-padding-top }` then stands in for Lenis' anchor offset.
	const reduced = window.matchMedia('(prefers-reduced-motion: reduce)');

	const sync = () => {
		lenis?.destroy();
		lenis = null;
		if (reduced.matches) return;
		lenis = new Lenis({
			autoRaf: true,
			anchors: { offset: -HEADER_OFFSET },
			allowNestedScroll: true
		});
	};

	sync();
	reduced.addEventListener('change', sync);

	return () => {
		reduced.removeEventListener('change', sync);
		lenis?.destroy();
		lenis = null;
	};
}

let locks = 0;

export function lockPageScroll(): () => void {
	if (++locks === 1) {
		document.body.style.overflow = 'hidden';
		lenis?.stop();
	}

	let released = false;

	return () => {
		if (released) return;
		released = true;
		if (--locks === 0) {
			document.body.style.overflow = '';
			lenis?.start();
		}
	};
}
