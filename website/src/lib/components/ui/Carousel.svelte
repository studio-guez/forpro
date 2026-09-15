<script lang="ts" generics="T">
	import type { Snippet } from 'svelte';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';

	interface Props {
		items: T[];
		/** Rendered for each slide. */
		item: Snippet<[T, number]>;
		label: string;
		/** Tailwind classes sizing a single slide, e.g. `w-3/4 lg:w-1/3`. */
		itemClass?: string;
		/** Accent colour of the controls. */
		color?: string;
		/** Controls sit on a coloured background: white circles with an accent icon. */
		inverted?: boolean;
		class?: string;
	}

	let {
		items,
		item,
		label,
		itemClass = '',
		color = 'var(--color-blue)',
		inverted = false,
		class: className = ''
	}: Props = $props();

	let track: HTMLUListElement | undefined = $state();
	let index = $state(0);
	// Usually less than `items.length`: with several slides visible the last ones can never lead.
	// svelte-ignore state_referenced_locally
	let steps = $state(items.length);

	const slides = (): HTMLElement[] => (track ? (Array.from(track.children) as HTMLElement[]) : []);
	const snapOffset = (slide: HTMLElement, all: HTMLElement[]) =>
		slide.offsetLeft - all[0].offsetLeft;
	const maxScroll = () => (track ? track.scrollWidth - track.clientWidth : 0);

	const goTo = (i: number) => {
		const all = slides();
		const target = all[Math.min(Math.max(i, 0), steps - 1)];
		if (!target || !track) return;
		const behavior = window.matchMedia('(prefers-reduced-motion: reduce)').matches
			? 'auto'
			: 'smooth';
		track.scrollTo({ left: snapOffset(target, all), behavior });
	};

	const onScroll = () => {
		if (!track) return;
		const all = slides();
		if (all.length === 0) return;
		// The end of the track is its own position: the browser clamps the remaining snap points onto it.
		if (track.scrollLeft >= maxScroll() - 1) {
			index = steps - 1;
			return;
		}
		const closest = all.reduce(
			(closest, slide, i) =>
				Math.abs(snapOffset(slide, all) - track!.scrollLeft) <
				Math.abs(snapOffset(all[closest], all) - track!.scrollLeft)
					? i
					: closest,
			0
		);
		index = Math.min(closest, steps - 1);
	};

	const measure = () => {
		if (!track) return;
		const all = slides();
		if (all.length === 0) return;
		const beyond = all.findIndex((slide) => snapOffset(slide, all) >= maxScroll() - 1);
		steps = beyond === -1 ? all.length : beyond + 1;
		onScroll();
	};

	$effect(() => {
		if (!track) return;
		void items;
		const observer = new ResizeObserver(measure);
		for (const element of [track, ...slides()]) observer.observe(element);
		return () => observer.disconnect();
	});

	const circleClass = $derived(
		inverted
			? 'bg-white text-(--carousel-color) hover:bg-(--carousel-color) hover:text-white'
			: 'bg-(--carousel-color) text-white hover:opacity-80'
	);
	const dotClass = $derived(inverted ? 'bg-white' : 'bg-(--carousel-color)');
</script>

{#snippet arrow(direction: 'prev' | 'next', extraClass: string)}
	<button
		type="button"
		onclick={() => goTo(direction === 'prev' ? index - 1 : index + 1)}
		disabled={direction === 'prev' ? index === 0 : index >= steps - 1}
		aria-label={direction === 'prev' ? 'Précédent' : 'Suivant'}
		class="w-11 h-11 lg:w-13 lg:h-13 shrink-0 rounded-full flex items-center justify-center transition disabled:opacity-40 {circleClass} {extraClass}"
	>
		<IconChevron class="w-7 h-7 {direction === 'prev' ? 'rotate-90' : '-rotate-90'}" />
	</button>
{/snippet}

<div class={className} style="--carousel-color: {color}">
	<div class="relative -mx-card">
		<ul
			bind:this={track}
			onscroll={onScroll}
			aria-label={label}
			class="flex gap-4 lg:gap-6 px-card scroll-px-card overflow-x-auto snap-x snap-mandatory [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
		>
			{#each items as entry, i (i)}
				<li class="snap-start shrink-0 {itemClass}">
					{@render item(entry, i)}
				</li>
			{/each}
		</ul>

		{#if steps > 1}
			{@render arrow('prev', 'hidden lg:flex absolute left-8 top-1/2 -translate-y-1/2')}
			{@render arrow('next', 'hidden lg:flex absolute right-8 top-1/2 -translate-y-1/2')}
		{/if}
	</div>

	{#if steps > 1}
		<div class="mt-6 flex items-center justify-between lg:justify-center gap-4">
			{@render arrow('prev', 'lg:hidden')}

			<ol class="flex items-center gap-2.5">
				{#each { length: steps }, i (i)}
					<li>
						<button
							type="button"
							onclick={() => goTo(i)}
							aria-label="Aller à l'élément {i + 1}"
							aria-current={i === index ? 'true' : undefined}
							class="w-2.5 h-2.5 rounded-full transition-opacity {dotClass}"
							class:opacity-30={i !== index}
						></button>
					</li>
				{/each}
			</ol>

			{@render arrow('next', 'lg:hidden')}
		</div>
	{/if}
</div>
