<script lang="ts">
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import ShapeStep1 from '$lib/components/svg/ShapeStep1.svelte';
	import ShapeStep2 from '$lib/components/svg/ShapeStep2.svelte';
	import ShapeStep3 from '$lib/components/svg/ShapeStep3.svelte';
	import ShapeStep4 from '$lib/components/svg/ShapeStep4.svelte';
	import ShapeStep5 from '$lib/components/svg/ShapeStep5.svelte';
	import ArrowStepDesktop1 from '$lib/components/svg/ArrowStepDesktop1.svelte';
	import ArrowStepDesktop2 from '$lib/components/svg/ArrowStepDesktop2.svelte';
	import ArrowStepDesktop3 from '$lib/components/svg/ArrowStepDesktop3.svelte';
	import ArrowStepDesktop4 from '$lib/components/svg/ArrowStepDesktop4.svelte';
	import ArrowStepMobile1 from '$lib/components/svg/ArrowStepMobile1.svelte';
	import ArrowStepMobile2 from '$lib/components/svg/ArrowStepMobile2.svelte';
	import ArrowStepMobile3 from '$lib/components/svg/ArrowStepMobile3.svelte';
	import ArrowStepMobile4 from '$lib/components/svg/ArrowStepMobile4.svelte';
	import ArrowStepMobile5 from '$lib/components/svg/ArrowStepMobile5.svelte';
	import type { JobOfferStep } from '$lib/interfaces/jobOffers';

	interface Props {
		steps: JobOfferStep[];
		title?: string;
	}

	let { steps, title = 'Étapes du recrutement' }: Props = $props();

	// Shapes and connectors are cycled, so any number of steps keeps the rhythm
	// of the design. The desktop arrows alternate down/up, which matches the
	// two-row zigzag: odd steps sit on the top row, even ones on the bottom row.
	const shapes = [ShapeStep1, ShapeStep2, ShapeStep3, ShapeStep4, ShapeStep5];
	const desktopArrows = [ArrowStepDesktop1, ArrowStepDesktop2, ArrowStepDesktop3, ArrowStepDesktop4];
	const mobileArrows = [
		ArrowStepMobile1,
		ArrowStepMobile2,
		ArrowStepMobile3,
		ArrowStepMobile4,
		ArrowStepMobile5
	];

	let track: HTMLOListElement | undefined = $state();

	const scrollBy = (direction: -1 | 1): void => {
		if (!track) return;
		// One column at a time; every column holds two rows of the zigzag.
		track.scrollBy({ left: direction * (track.clientWidth / 3), behavior: 'smooth' });
	};

	let scrollLeft = $state(0);
	let scrollable = $state(false);

	const onScroll = (): void => {
		if (!track) return;
		scrollLeft = track.scrollLeft;
		scrollable = track.scrollWidth > track.clientWidth + 1;
	};

	const atStart = $derived(scrollLeft <= 1);
	const atEnd = $derived(
		!track || scrollLeft >= track.scrollWidth - track.clientWidth - 1
	);
</script>

{#snippet stepContent(step: JobOfferStep, index: number)}
	{@const Shape = shapes[index % shapes.length]}
	<div class="relative aspect-square w-full text-green">
		<Shape class="absolute inset-0 w-full h-full" />
		<div class="absolute inset-0 flex flex-col justify-center text-blue px-[18%]">
			<p class="text-body-1 md:text-h4">{step.title}</p>
			{#if step.shortDesc}
				<div class="prose text-caption md:text-label font-bold mt-1 md:mt-2">
					{@html step.shortDesc}
				</div>
			{/if}
		</div>
	</div>
{/snippet}

{#if steps.length > 0}
	<section aria-labelledby="recruiting-steps-title" class="px-card">
		<h2 id="recruiting-steps-title" class="text-h3 text-center">
			<span class="inline-block bg-blue text-white rounded-2xl px-8 pt-2 pb-3.5">{title}</span>
		</h2>

		<!-- Mobile: a 3-column grid the steps zigzag through, two columns wide each. -->
		<ol class="md:hidden mt-12 grid grid-cols-3 gap-y-14">
			{#each steps as step, i (i)}
				{@const isLeft = i % 2 === 0}
				{@const Arrow = mobileArrows[i % mobileArrows.length]}
				<li class="relative col-span-2 {isLeft ? 'col-start-1' : 'col-start-2'}">
					{@render stepContent(step, i)}
					{#if i < steps.length - 1}
						<div
							class="absolute top-[85%] w-[38%] text-pink {isLeft
								? 'left-[80%]'
								: 'right-[80%] -scale-x-100'}"
						>
							<Arrow class="w-full h-auto" />
						</div>
					{/if}
				</li>
			{/each}
		</ol>

		<!-- Desktop: two rows, odd steps on top, even ones below, scrolling sideways. -->
		<div class="max-md:hidden relative mt-16">
			<ol
				bind:this={track}
				onscroll={onScroll}
				class="grid grid-rows-2 grid-flow-col auto-cols-[minmax(15rem,1fr)] gap-x-8 gap-y-16 overflow-x-auto snap-x [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
			>
				{#each steps as step, i (i)}
					{@const isTop = i % 2 === 0}
					{@const Arrow = desktopArrows[i % desktopArrows.length]}
					<li class="relative snap-start {isTop ? 'row-start-1' : 'row-start-2'}">
						{@render stepContent(step, i)}
						{#if i < steps.length - 1}
							<div
								class="absolute left-[72%] w-[52%] text-pink pointer-events-none {isTop
									? 'top-[62%]'
									: 'bottom-[62%]'}"
							>
								<Arrow class="w-full h-auto" />
							</div>
						{/if}
					</li>
				{/each}
			</ol>

			{#if scrollable}
				<div class="mt-6 flex items-center justify-between">
					<button
						type="button"
						onclick={() => scrollBy(-1)}
						disabled={atStart}
						aria-label="Étapes précédentes"
						class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center bg-blue text-white transition hover:opacity-80 disabled:opacity-40"
					>
						<IconChevron width={28} height={28} class="rotate-90" />
					</button>
					<button
						type="button"
						onclick={() => scrollBy(1)}
						disabled={atEnd}
						aria-label="Étapes suivantes"
						class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center bg-blue text-white transition hover:opacity-80 disabled:opacity-40"
					>
						<IconChevron width={28} height={28} class="-rotate-90" />
					</button>
				</div>
			{/if}
		</div>
	</section>
{/if}
