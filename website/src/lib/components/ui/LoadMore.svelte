<script lang="ts">
	interface Props {
		/** Whether more items can be revealed. */
		hasMore: boolean;
		/** Called when the sentinel enters the viewport, or on button click. */
		loadMore: () => void;
		label?: string;
		color?: string;
		class?: string;
	}

	let {
		hasMore,
		loadMore,
		label = 'Voir plus',
		color = 'var(--color-teal)',
		class: className = ''
	}: Props = $props();

	let sentinel: HTMLDivElement | undefined = $state();

	// Auto-scroll pagination: reveal the next page as soon as the end of the list
	// is approached. The button stays as a keyboard/no-observer fallback.
	$effect(() => {
		if (!sentinel || !hasMore || typeof IntersectionObserver === 'undefined') return;

		const observer = new IntersectionObserver(
			(entries) => {
				if (entries.some((entry) => entry.isIntersecting)) loadMore();
			},
			{ rootMargin: '400px 0px' }
		);
		observer.observe(sentinel);

		return () => observer.disconnect();
	});
</script>

{#if hasMore}
	<div style:--load-more-color={color} class="flex justify-center {className}">
		<div bind:this={sentinel} aria-hidden="true"></div>
		<button
			type="button"
			class="text-label rounded-full border-2 border-(--load-more-color) text-(--load-more-color) px-6 py-2 leading-tight transition-colors hover:bg-(--load-more-color) hover:text-white"
			onclick={loadMore}
		>
			{label}
		</button>
	</div>
{/if}
