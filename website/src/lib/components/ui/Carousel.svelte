<script lang="ts" generics="T">
	import type { Snippet } from 'svelte';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';

	interface Props {
		items: T[];
		/** Rendered for each slide. */
		item: Snippet<[T, number]>;
		label: string;
		/** Tailwind classes sizing a single slide, e.g. `w-3/4 md:w-1/3`. */
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

	const slides = (): HTMLElement[] => (track ? (Array.from(track.children) as HTMLElement[]) : []);

	const goTo = (i: number) => {
		const target = slides()[Math.min(Math.max(i, 0), items.length - 1)];
		if (!target || !track) return;
		track.scrollTo({ left: target.offsetLeft - slides()[0].offsetLeft, behavior: 'smooth' });
	};

	// The scroll position is the source of truth: it also covers swipes and keyboard scrolling.
	const onScroll = () => {
		if (!track) return;
		const all = slides();
		if (all.length === 0) return;
		const origin = all[0].offsetLeft;
		index = all.reduce(
			(closest, slide, i) =>
				Math.abs(slide.offsetLeft - origin - track!.scrollLeft) <
				Math.abs(all[closest].offsetLeft - origin - track!.scrollLeft)
					? i
					: closest,
			0
		);
	};

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
		disabled={direction === 'prev' ? index === 0 : index >= items.length - 1}
		aria-label={direction === 'prev' ? 'Précédent' : 'Suivant'}
		class="w-11 h-11 md:w-13 md:h-13 shrink-0 rounded-full flex items-center justify-center transition disabled:opacity-40 {circleClass} {extraClass}"
	>
		<IconChevron
			width={28}
			height={28}
			class={direction === 'prev' ? 'rotate-90' : '-rotate-90'}
		/>
	</button>
{/snippet}

<div class={className} style="--carousel-color: {color}">
	<div class="relative -mx-card">
		<ul
			bind:this={track}
			onscroll={onScroll}
			aria-label={label}
			class="flex gap-4 md:gap-6 px-card scroll-px-card overflow-x-auto snap-x snap-mandatory [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
		>
			{#each items as entry, i (i)}
				<li class="snap-start shrink-0 {itemClass}">
					{@render item(entry, i)}
				</li>
			{/each}
		</ul>

		{@render arrow('prev', 'hidden md:flex absolute left-8 top-1/2 -translate-y-1/2')}
		{@render arrow('next', 'hidden md:flex absolute right-8 top-1/2 -translate-y-1/2')}
	</div>

	<div class="mt-6 flex items-center justify-between md:justify-center gap-4">
		{@render arrow('prev', 'md:hidden')}

		<ol class="flex items-center gap-2.5">
			{#each items as _, i (i)}
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

		{@render arrow('next', 'md:hidden')}
	</div>
</div>
