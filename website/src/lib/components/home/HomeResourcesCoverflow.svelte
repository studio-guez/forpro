<script lang="ts">
	import type { HomeResourceCard as HomeResourceCardData } from '$lib/interfaces/home';
	import HomeResourceCard from '$lib/components/home/HomeResourceCard.svelte';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';

	interface Props {
		resources: HomeResourceCardData[];
		label: string;
		/** Time a card stays in front before the deck turns on its own, in ms. */
		interval?: number;
		class?: string;
	}

	let { resources, label, interval = 2250, class: className = '' }: Props = $props();

	const count = $derived(resources.length);

	// Numbered among page cards, not by slot: the CMS shuffles pages and projects, so a slot index would repeat shapes.
	const pageRank = $derived(
		resources.map((_, i) => resources.slice(0, i).filter((o) => o.type === 'page').length)
	);

	// svelte-ignore state_referenced_locally
	let active = $state(Math.floor(resources.length / 2));

	const wrap = (i: number) => ((i % count) + count) % count;

	const offset = (i: number) => {
		const half = Math.floor(count / 2);
		return wrap(i - active + half) - half;
	};

	const goTo = (i: number) => {
		active = wrap(i);
	};

	// No auto spin on touch screens: without hover to hold it, the deck would turn under a thumb about to tap.
	let hovered = $state(false);
	let focused = $state(false);
	let reduced = $state(false);
	let touch = $state(false);
	const spinning = $derived(count > 1 && !hovered && !focused && !reduced && !touch);

	const watchMedia = (query: string, set: (matches: boolean) => void) => {
		const list = window.matchMedia(query);
		const sync = () => set(list.matches);
		sync();
		list.addEventListener('change', sync);
		return () => list.removeEventListener('change', sync);
	};

	$effect(() => watchMedia('(prefers-reduced-motion: reduce)', (m) => (reduced = m)));
	$effect(() => watchMedia('(hover: none)', (m) => (touch = m)));

	$effect(() => {
		if (!spinning) return;
		void active;
		const id = setInterval(() => goTo(active + 1), interval);
		return () => clearInterval(id);
	});

	// Keyboard focus only: a mouse press focuses the link too, and turning the deck then would pull the card from under the click.
	const onCardFocus = (event: FocusEvent, i: number) => {
		if ((event.target as HTMLElement).matches(':focus-visible')) goTo(i);
	};

	const onFocusOut = (event: FocusEvent) => {
		const stage = event.currentTarget as HTMLElement;
		if (!stage.contains(event.relatedTarget as Node | null)) focused = false;
	};

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

	const cardSizes = '(min-width: 1024px) 22rem, 16rem';
	const deckId = 'home-resources-deck';
	const descId = (i: number) => `${deckId}-desc-${i}`;
</script>

{#snippet arrow(direction: 'prev' | 'next', extraClass: string)}
	<button
		type="button"
		onclick={() => goTo(direction === 'prev' ? active - 1 : active + 1)}
		aria-label={direction === 'prev' ? 'Précédent' : 'Suivant'}
		aria-controls={deckId}
		class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center transition-colors text-blue hover:bg-blue hover:text-white {extraClass}"
	>
		<IconChevron
			class="w-7 h-7 {direction === 'prev'
				? 'rotate-90 translate-x-px'
				: '-rotate-90 -translate-x-px'}"
		/>
	</button>
{/snippet}

<div
	role="group"
	aria-roledescription="carrousel"
	aria-label={label}
	class={className}
	onpointerenter={() => (hovered = true)}
	onpointerleave={() => (hovered = false)}
	onfocusin={() => (focused = true)}
	onfocusout={onFocusOut}
>
	<div class="relative overflow-hidden py-2">
		<ul
			id={deckId}
			onpointerdown={onPointerDown}
			onpointerup={onPointerUp}
			onpointercancel={() => (pointerStartX = null)}
			class="grid touch-pan-y select-none [--step:15%] sm:[--step:40%] md:[--step:50%] lg:[--step:40%] xl:[--step:60%]"
		>
			{#each resources as resource, i (i)}
				{@const d = offset(i)}
				<li
					class="col-start-1 row-start-1 justify-self-center w-60 lg:w-88 transition-[translate,scale] duration-500 ease-out motion-reduce:transition-none"
					style:translate="calc({d} * var(--step)) {Math.abs(d) * 2}%"
					style:scale={1 - Math.abs(d) * 0.1}
					style:z-index={10 - Math.abs(d)}
					aria-current={d === 0 ? 'true' : undefined}
					onfocusin={(event) => onCardFocus(event, i)}
				>
					<HomeResourceCard
						{resource}
						index={pageRank[i]}
						sizes={cardSizes}
						describedBy={resource.shortDesc ? descId(i) : undefined}
					/>
				</li>
			{/each}
		</ul>

		{#if count > 1}
			{@render arrow('prev', 'absolute left-2 lg:left-9 top-1/2 -translate-y-1/2 z-20')}
			{@render arrow('next', 'absolute right-2 lg:right-9 top-1/2 -translate-y-1/2 z-20')}
		{/if}
	</div>

	<!--
		Each description is the accessible description of its card (aria-describedby), even while hidden.
		The live region is a bonus for arrow-button users: it is muted while the deck spins on its own and
		names the card so the announced text is never an orphan. All descriptions share one grid cell so
		the height never jumps.
	-->
	<div aria-live={spinning ? 'off' : 'polite'} class="mt-6 lg:mt-9 px-card grid">
		{#each resources as resource, i (i)}
			{#if resource.shortDesc}
				<div
					class="col-start-1 row-start-1 prose text-sm lg:text-base text-center max-w-md mx-auto transition-opacity duration-200 ease-out"
					class:opacity-0={i !== active}
					inert={i !== active}
				>
					<span class="sr-only">{resource.title}&nbsp;: </span>
					<div id={descId(i)}>{@html resource.shortDesc}</div>
				</div>
			{/if}
		{/each}
	</div>
</div>
