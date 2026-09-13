<script lang="ts">
	import type { HomeResourceCard as HomeResourceCardData } from '$lib/interfaces/home';
	import HomeResourceCard from '$lib/components/home/HomeResourceCard.svelte';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';

	interface Props {
		resources: HomeResourceCardData[];
		label: string;
		class?: string;
	}

	let { resources, label, class: className = '' }: Props = $props();

	const count = $derived(resources.length);

	// The middle card leads: with the 5 the CMS sends that is 2 on each side.
	// svelte-ignore state_referenced_locally
	let active = $state(Math.floor(resources.length / 2));

	const wrap = (i: number) => ((i % count) + count) % count;

	// Signed distance from the active slot, wrapped so the deck is circular: the card that
	// just left on one side re-enters on the other, and the arrows never run out.
	const offset = (i: number) => {
		const half = Math.floor(count / 2);
		return wrap(i - active + half) - half;
	};

	const goTo = (i: number) => {
		active = wrap(i);
	};

	// Swipe on the stage: a horizontal drag past the threshold turns the deck one step.
	let pointerStartX: number | null = null;
	const onPointerDown = (event: PointerEvent) => {
		pointerStartX = event.clientX;
	};
	const onPointerUp = (event: PointerEvent) => {
		if (pointerStartX === null) return;
		const delta = event.clientX - pointerStartX;
		pointerStartX = null;
		if (Math.abs(delta) < 40) return;
		goTo(delta < 0 ? active + 1 : active - 1);
	};

	// A click on a side card brings it to the front rather than leaving the page; the
	// active card stays a plain link.
	const onClickCapture = (event: MouseEvent, i: number) => {
		if (i === active) return;
		event.preventDefault();
		goTo(i);
	};

	const cardSizes = '(min-width: 1024px) 22rem, 16rem';
</script>

{#snippet arrow(direction: 'prev' | 'next', extraClass: string)}
	<button
		type="button"
		onclick={() => goTo(direction === 'prev' ? active - 1 : active + 1)}
		aria-label={direction === 'prev' ? 'Précédent' : 'Suivant'}
		class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center transition-colors text-blue hover:bg-blue hover:text-white {extraClass}"
	>
		<IconChevron
			class="w-7 h-7 {direction === 'prev'
				? 'rotate-90 translate-x-px'
				: '-rotate-90 -translate-x-px'}"
		/>
	</button>
{/snippet}

<div class={className}>
	<!-- Clipped: the two outermost cards reach past a phone screen, and the page must
		 never scroll sideways. -->
	<div class="relative overflow-hidden py-2">
		<ul
			aria-label={label}
			onpointerdown={onPointerDown}
			onpointerup={onPointerUp}
			onpointercancel={() => (pointerStartX = null)}
			class="grid touch-pan-y select-none [--step:15%] sm:[--step:40%] md:[--step:50%] lg:[--step:40%] xl:[--step:60%]"
		>
			{#each resources as resource, i (i)}
				{@const d = offset(i)}
				<!-- Every card shares the one grid cell; the offset fans them out from there. -->
				<li
					class="col-start-1 row-start-1 justify-self-center w-50 sm:w-60 lg:w-88 transition-[translate,scale] duration-500 ease-out"
					class:pointer-events-none={Math.abs(d) === 2}
					style:translate="calc({d} * var(--step)) {Math.abs(d) * 2}%"
					style:scale={1 - Math.abs(d) * 0.1}
					style:z-index={10 - Math.abs(d)}
					aria-current={d === 0 ? 'true' : undefined}
					onclickcapture={(event) => onClickCapture(event, i)}
					onfocusin={() => goTo(i)}
				>
					<HomeResourceCard
						{resource}
						index={i}
						sizes={cardSizes}
						tabindex={d === 0 ? undefined : -1}
					/>
				</li>
			{/each}
		</ul>

		{#if count > 1}
			{@render arrow('prev', 'absolute left-2 lg:left-9 top-1/2 -translate-y-1/2 z-20')}
			{@render arrow('next', 'absolute right-2 lg:right-9 top-1/2 -translate-y-1/2 z-20')}
		{/if}
	</div>

	<!-- What the leading card is about; announced when the deck turns. Every description
		 shares the one grid cell, so the block keeps the height of the tallest one and the
		 page never jumps when the deck turns; only the active one is visible, cross-fading
		 with the previous. `inert` keeps the hidden ones out of the tab order and the
		 accessibility tree. -->
	<div aria-live="polite" class="mt-6 lg:mt-9 px-card grid">
		{#each resources as resource, i (i)}
			{#if resource.shortDesc}
				<div
					class="col-start-1 row-start-1 prose text-caption text-center max-w-md mx-auto transition-opacity duration-500 ease-out"
					class:opacity-0={i !== active}
					inert={i !== active}
				>
					{@html resource.shortDesc}
				</div>
			{/if}
		{/each}
	</div>
</div>
