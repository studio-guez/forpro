import Lenis from 'lenis';

/** Height of the fixed `SiteHeader` (`main` sits at `pt-27` = 6.75rem), so anchors land under it. */
const HEADER_OFFSET = 108;

let lenis: Lenis | null = null;

/**
 * Gets a wheel tick before Lenis turns it into page scroll: the vertical delta and the scroll
 * position the page is easing towards. Returns the part of the delta the page should still scroll.
 */
export type WheelInterceptor = (deltaY: number, pageTarget: number) => number;

const wheelInterceptors = new Set<WheelInterceptor>();

/** Returns its teardown, so it can be returned straight from an `$effect`. */
export function interceptWheel(interceptor: WheelInterceptor): () => void {
	wheelInterceptors.add(interceptor);
	return () => {
		wheelInterceptors.delete(interceptor);
	};
}

/**
 * Boots the smooth scroller on the window scroller.
 * Returns its teardown, so it can be returned straight from `onMount`.
 */
export function initSmoothScroll(): () => void {
	lenis?.destroy();
	lenis = new Lenis({
		autoRaf: true,
		anchors: { offset: -HEADER_OFFSET },
		allowNestedScroll: true,
		virtualScroll: (data) => {
			const { event } = data;
			if (!lenis || lenis.isStopped || !(event instanceof WheelEvent) || event.ctrlKey) return true;
			if (Math.abs(data.deltaX) > Math.abs(data.deltaY)) return true;

			let deltaY = data.deltaY;
			for (const intercept of wheelInterceptors) deltaY = intercept(deltaY, lenis.targetScroll);
			if (deltaY !== 0) {
				data.deltaY = deltaY;
				return true;
			}
			// A zero delta reads as a click to Lenis, which would then let the native scroll through.
			if (event.cancelable) event.preventDefault();
			return false;
		}
	});

	return () => {
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
