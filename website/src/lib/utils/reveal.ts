import type { Action } from 'svelte/action';

/**
 * Drives a one-shot entrance: `data-reveal` goes `pending` on mount and `done` the first
 * time the element scrolls into view, after which the observer is dropped so the entrance
 * plays once, not on every pass. The element's CSS keys its offset state on
 * `[data-reveal='pending']` (see `HomeWelcomeCards`).
 *
 * Two cases skip `pending` on purpose. Without JavaScript the attribute never appears
 * and the element simply renders in its final state. And an element already in view at
 * hydration time goes straight to `done`: the server-rendered page has shown it in place
 * already, so pushing it out and sliding it back in would read as a glitch, not an entrance.
 */
export const reveal: Action<HTMLElement> = (node) => {
	const { top } = node.getBoundingClientRect();
	if (top < window.innerHeight * 0.9) {
		node.dataset.reveal = 'done';
		return;
	}

	node.dataset.reveal = 'pending';

	const observer = new IntersectionObserver(
		(entries) => {
			if (!entries.some((entry) => entry.isIntersecting)) return;
			node.dataset.reveal = 'done';
			observer.disconnect();
		},
		{ threshold: 0.15 }
	);
	observer.observe(node);

	return {
		destroy: () => observer.disconnect()
	};
};
