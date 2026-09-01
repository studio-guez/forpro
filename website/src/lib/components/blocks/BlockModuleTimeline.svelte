<script lang="ts">
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
	import type { ModuleTimelineContent, Theme, TimelineStep } from '$lib/interfaces/page';

	interface Props {
		content: ModuleTimelineContent;
		theme?: Theme;
	}

	let { content, theme = 'default' }: Props = $props();

	const steps = $derived(content.steps);

	// Only `projets_jeunes` departs from the default palette; every other theme keeps
	// the green/blue/pink set of the design.
	const palettes = {
		default: {
			shape: 'text-green',
			step: 'text-blue',
			pill: 'bg-blue text-white',
			arrow: 'text-pink'
		},
		projets_jeunes: {
			shape: 'text-orange-pale',
			step: 'text-orange',
			pill: 'bg-orange text-white',
			arrow: 'text-orange'
		}
	} as const;

	const colors = $derived(theme === 'projets_jeunes' ? palettes.projets_jeunes : palettes.default);

	// Shapes and connectors are cycled, so any number of steps keeps the rhythm
	// of the design. The desktop arrows alternate down/up, which matches the
	// zigzag: odd steps sit on the top row, even ones on the bottom row.
	const shapes = [ShapeStep1, ShapeStep2, ShapeStep3, ShapeStep4, ShapeStep5];
	const desktopArrows = [
		ArrowStepDesktop1,
		ArrowStepDesktop2,
		ArrowStepDesktop3,
		ArrowStepDesktop4
	];
	const mobileArrows = [
		ArrowStepMobile1,
		ArrowStepMobile2,
		ArrowStepMobile3,
		ArrowStepMobile4,
		ArrowStepMobile5
	];
	/**
	 * Which way each mobile connector is drawn. A step on the left needs one pointing
	 * right and vice versa, so the ones that come out of the cycle facing the wrong way
	 * are mirrored rather than duplicated as extra assets.
	 */
	const mobileArrowPointsLeft = [true, false, true, false, true];

	/** Scroll runway: as tall as the horizontal distance the steps have to travel. */
	let spacer: HTMLDivElement | undefined = $state();
	/** The part that stays put under the header while that runway is consumed. */
	let stage: HTMLDivElement | undefined = $state();
	let track: HTMLOListElement | undefined = $state();

	let overflow = $state(0);
	let stageHeight = $state(0);

	/** Page scroll swallowed per pixel of horizontal travel: above 1 the steps drift by slower. */
	const PACE = 2;

	/** Length of the runway, i.e. how long the block stays pinned. */
	const runway = $derived(overflow * PACE);

	/**
	 * While the stage is stuck, the distance it has travelled inside the spacer is
	 * exactly the page scroll it has swallowed — the browser clamps it to
	 * `spacer height - stage height`, i.e. to `runway`. Feeding it back as
	 * `scrollLeft` turns that vertical scroll into a horizontal one.
	 */
	const sync = (): void => {
		if (!spacer || !stage || !track || overflow <= 0) return;
		const scrolled = stage.getBoundingClientRect().top - spacer.getBoundingClientRect().top;
		track.scrollLeft = Math.min(Math.max(scrolled, 0), runway) / PACE;
	};

	const measure = (): void => {
		if (!track || !stage) return;
		// Both are 0 while the desktop layout is hidden, which leaves the block unpinned.
		overflow = Math.max(track.scrollWidth - track.clientWidth, 0);
		stageHeight = stage.offsetHeight;
		sync();
	};

	$effect(() => {
		const el = stage;
		if (!el) return;

		measure();
		const observer = new ResizeObserver(measure);
		observer.observe(el);
		if (track) observer.observe(track);

		return () => observer.disconnect();
	});
</script>

<svelte:window onscroll={sync} onresize={measure} />

{#snippet title()}
	<h2 class={['text-h3 text-center px-card relative z-1', content.hideTitle && 'sr-only']}>
		<span class="inline-block rounded-2xl px-8 pt-2 pb-3.5 {colors.pill}">{content.title}</span>
	</h2>
{/snippet}

{#snippet stepContent(step: TimelineStep, index: number)}
	{@const Shape = shapes[index % shapes.length]}
	<!--
		The step is a container and its type is sized in `cqw`, i.e. as a share of the
		step itself: the copy keeps the `text-h4` / `text-body-1` proportions of the
		design at full size and still sits inside the blob once the step shrinks.
	-->
	<div class="@container relative aspect-square w-full {colors.shape}">
		<!-- The blob is drawn wider than the text box so the copy sits well inside it. -->
		<div class="absolute -inset-2/25 lg:-inset-1/8">
			<Shape class="w-full h-full" />
		</div>
		<div class="absolute inset-0 flex flex-col justify-center px-[15%] {colors.step}">
			<p class="text-[9cqw]/[1.05] font-bold">{step.title}</p>
			{#if step.shortDesc}
				<p class="text-[6.6cqw]/[1.2] font-bold mt-[5cqw] whitespace-pre-line">
					{step.shortDesc}
				</p>
			{/if}
		</div>
	</div>
{/snippet}

{#if steps.length > 0}
	<section aria-label={content.title} class="max-w-none">
		<!--
			Mobile: one step per row, alternating sides. Narrow screens give each step two
			of three columns, from `md` the grid halves so the steps stay a sensible size.
			The wrapper clips because the blobs are drawn past their box, and that overhang
			must not turn into a horizontal page scroll.
		-->
		<div class="lg:hidden overflow-x-clip">
			{@render title()}
			<ol class="px-card mt-12 grid grid-cols-3 md:grid-cols-2 gap-y-16 md:gap-y-24">
				{#each steps as step, i (i)}
					{@const isLeft = i % 2 === 0}
					{@const arrowIndex = i % mobileArrows.length}
					{@const Arrow = mobileArrows[arrowIndex]}
					{@const mirrored = mobileArrowPointsLeft[arrowIndex] === isLeft}
					<li
						class="relative col-span-2 md:col-span-1 {isLeft
							? 'col-start-1'
							: 'col-start-2 md:col-start-2'}"
						style="grid-row: {i + 1}"
					>
						{@render stepContent(step, i)}
						{#if i < steps.length - 1}
							<!-- The connector hangs off the corner that faces the next step. -->
							<div
								class="absolute top-[78%] h-[48%] z-10 {colors.arrow} {isLeft
									? 'left-[62%]'
									: 'right-[62%]'} {mirrored ? '-scale-x-100' : ''}"
							>
								<Arrow class="h-full w-auto" />
							</div>
						{/if}
					</li>
				{/each}
			</ol>
		</div>

		<!--
			Desktop: one step per column, odd steps on the top row, even ones below.
			The block runs edge to edge; only the first column keeps the page gutter so
			it lines up with the rest of the content.
		-->
		<div
			bind:this={spacer}
			class="max-lg:hidden"
			style={overflow > 0 ? `height: ${stageHeight + runway}px` : ''}
		>
			<div
				bind:this={stage}
				class="sticky top-27 flex min-h-[calc(100vh-12rem)] flex-col justify-start gap-6"
			>
				{@render title()}
				<!--
					Every distance is a fraction of `--step`, so the zigzag and the arrows
					drawn in its empty cells keep their proportions whatever the step size.
				-->
				<ol
					bind:this={track}
					style="--step: min(22.5rem, calc(40vh - 120px))"
					class="grid grid-rows-2 auto-cols-[var(--step)] gap-x-[calc(var(--step)*0.16)] gap-y-[calc(var(--step)*0.18)] py-[calc(var(--step)*0.12)] px-card-bleed overflow-x-auto overflow-y-clip scrollbar-none"
				>
					{#each steps as step, i (i)}
						{@const isTop = i % 2 === 0}
						{@const Arrow = desktopArrows[i % desktopArrows.length]}
						<!-- The column is set explicitly so a step never stacks under its neighbour. -->
						<li
							class="relative {isTop ? 'row-start-1' : 'row-start-2'}"
							style="grid-column: {i + 1}"
						>
							{@render stepContent(step, i)}
							{#if i < steps.length - 1}
								<!-- The connector is drawn in the empty cell the zigzag leaves free. -->
								<div
									class="absolute pointer-events-none {colors.arrow} {isTop
										? 'left-[18%] w-[70%] top-[110%]'
										: 'left-[10%] w-[65%] bottom-[120%]'}"
								>
									<Arrow class="w-full h-auto" />
								</div>
							{/if}
						</li>
					{/each}
				</ol>
			</div>
		</div>
	</section>
{/if}
