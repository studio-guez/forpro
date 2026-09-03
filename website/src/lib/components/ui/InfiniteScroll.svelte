<script lang="ts">
	import IconSpinner from '$lib/components/svg/IconSpinner.svelte';

	interface Props {
		/** Whether another page can be pulled. */
		hasMore: boolean;
		/** Called when the sentinel comes into range. */
		loadMore: () => void;
		/**
		 * Changes once per appended page — the item count. An observer only
		 * reports *changes* of intersection, so a page too short to push the
		 * sentinel back out of range would never fire again: re-observing on
		 * every page is what keeps a long list scrolling.
		 */
		key: unknown;
		/**
		 * The scroll container the sentinel is watched in. Defaults to the
		 * viewport, which is right for a list in the page flow; a list inside its
		 * own scroller (a modal) has to pass it, or the sentinel is measured
		 * against a viewport it never moves in.
		 */
		root?: Element | null;
		/** How early the next page is pulled, as an `IntersectionObserver` margin. */
		rootMargin?: string;
		label?: string;
		/** Accent colour of the spinner; the page's own, like every other control on it. */
		color?: string;
		class?: string;
	}

	let {
		hasMore,
		loadMore,
		key,
		root = null,
		rootMargin = '400px 0px',
		label = 'Chargement…',
		color = 'var(--color-teal)',
		class: className = ''
	}: Props = $props();

	let sentinel = $state<HTMLDivElement>();

	$effect(() => {
		void key;
		// A scroller passed as `root` may not be bound on the first run.
		if (!sentinel || !hasMore || root === undefined) return;

		let timer: ReturnType<typeof setTimeout> | undefined;
		const observer = new IntersectionObserver(
			(entries) => {
				clearTimeout(timer);
				// Debounced: a fast flick past the sentinel must not queue a fetch per page.
				if (entries[0].isIntersecting) timer = setTimeout(loadMore, 200);
			},
			// Pulled before the end of the list is actually reached, so the next
			// page is usually in place by the time it would have been needed.
			{ root, rootMargin }
		);
		observer.observe(sentinel);

		return () => {
			clearTimeout(timer);
			observer.disconnect();
		};
	});
</script>

<div bind:this={sentinel} class="h-px" aria-hidden="true"></div>

{#if hasMore}
	<p style:--spinner-color={color} class="text-(--spinner-color) flex justify-center {className}">
		<IconSpinner class="motion-safe:animate-spin w-8 h-8" />
		<span class="sr-only">{label}</span>
	</p>
{/if}
