import type { Action } from 'svelte/action';

interface RevealOptions {
	/**
	 * Where on the screen the entrance starts, as a fraction of the viewport height the
	 * element's top edge has to cross while scrolling up: `1` is the bottom edge (as soon
	 * as it shows), `0.5` the middle of the screen. Default `0.85`.
	 */
	trigger?: number;
}

/**
 * Drives a one-shot entrance: `data-reveal` goes `pending` on mount and `done` the first
 * time the element scrolls past the trigger line, after which the observer is dropped so
 * the entrance plays once, not on every pass. The element's CSS keys its offset state on
 * `[data-reveal='pending']` (see `HomeWelcomeCards`).
 *
 * Two cases skip `pending` on purpose. Without JavaScript the attribute never appears
 * and the element simply renders in its final state. And an element already on screen at
 * hydration time goes straight to `done`, whatever the trigger: the server-rendered page
 * has shown it in place already, so pushing it out and sliding it back in would read as a
 * glitch, not an entrance.
 */
export const reveal: Action<HTMLElement, RevealOptions | undefined> = (node, options) => {
	const trigger = options?.trigger ?? 0.85;

	const { top } = node.getBoundingClientRect();
	if (top < window.innerHeight) {
		node.dataset.reveal = 'done';
		return;
	}

	node.dataset.reveal = 'pending';

	// The root is shrunk from the bottom so that "intersecting" means the element's top
	// has crossed the trigger line, not merely the bottom edge of the viewport.
	const observer = new IntersectionObserver(
		(entries) => {
			if (!entries.some((entry) => entry.isIntersecting)) return;
			node.dataset.reveal = 'done';
			observer.disconnect();
		},
		{ rootMargin: `0px 0px ${-Math.round((1 - trigger) * 100)}% 0px` }
	);
	observer.observe(node);

	return {
		destroy: () => observer.disconnect()
	};
};
