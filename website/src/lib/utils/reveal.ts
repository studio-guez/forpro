import type { Action } from 'svelte/action';

interface RevealOptions {
	/**
	 * Where on the screen the entrance starts, as a fraction of the viewport height the
	 * element's top edge has to cross while scrolling up: `1` is the bottom edge (as soon
	 * as it shows), `0.5` the middle of the screen. Default `0.85`.
	 */
	trigger?: number;
	/**
	 * Gap, in milliseconds, before the next element's entrance when the whole group plays
	 * at hydration, so they come in one after the other instead of all at once. Default
	 * `150`.
	 */
	stagger?: number;
}

interface Pending {
	node: HTMLElement;
	trigger: number;
	stagger: number;
	/** Undoes whatever `flush` scheduled for this element; set once it has run. */
	cancel?: () => void;
}

// The frame gap lets the parked state get painted before `done` lands, otherwise nothing transitions.
let group: Pending[] = [];
let flushQueued = false;

const flush = () => {
	const batch = group;
	group = [];
	flushQueued = false;

	const onScreen = batch.some(({ node }) => {
		const { top, bottom } = node.getBoundingClientRect();
		return bottom > 0 && top < window.innerHeight;
	});

	let delay = 0;
	for (const entry of batch) {
		const { node, trigger, stagger } = entry;

		if (onScreen) {
			const timer = setTimeout(() => {
				node.dataset.reveal = 'done';
			}, delay);
			delay += stagger;
			entry.cancel = () => clearTimeout(timer);
			continue;
		}

		// The root is shrunk from the bottom so "intersecting" means the top has crossed the trigger line.
		const observer = new IntersectionObserver(
			(entries) => {
				if (!entries.some((e) => e.isIntersecting)) return;
				node.dataset.reveal = 'done';
				observer.disconnect();
			},
			{ rootMargin: `0px 0px ${-Math.round((1 - trigger) * 100)}% 0px` }
		);
		observer.observe(node);
		entry.cancel = () => observer.disconnect();
	}
};

/**
 * Drives a one-shot entrance: `data-reveal` goes `pending` on mount and `done` the first
 * time the element scrolls past the trigger line, after which the observer is dropped so
 * the entrance plays once, not on every pass. The element's CSS keys its offset state on
 * `[data-reveal='pending']` (see `HomeWelcomeCards`).
 *
 * Elements mounted together are a group. If any of them is on screen at hydration time,
 * none waits for the trigger line: they all play their entrance right away, in order and
 * `stagger` apart. Without JavaScript the attribute never appears, so the element simply
 * renders in its final state.
 */
export const reveal: Action<HTMLElement, RevealOptions | undefined> = (node, options) => {
	node.dataset.reveal = 'pending';

	const entry: Pending = {
		node,
		trigger: options?.trigger ?? 0.85,
		stagger: options?.stagger ?? 150
	};
	group.push(entry);

	if (!flushQueued) {
		flushQueued = true;
		// Two frames, not one: a callback queued from inside a frame callback runs in the next frame.
		requestAnimationFrame(() => requestAnimationFrame(flush));
	}

	return {
		destroy: () => {
			group = group.filter((pending) => pending !== entry);
			entry.cancel?.();
		}
	};
};
