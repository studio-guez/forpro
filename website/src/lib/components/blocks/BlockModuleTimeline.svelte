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

	/** Scroll runway: as tall as the horizontal distance the steps have to travel. */
	let spacer: HTMLDivElement | undefined = $state();
	/** The part that stays put under the header while that runway is consumed. */
	let stage: HTMLDivElement | undefined = $state();
	let track: HTMLOListElement | undefined = $state();

	let overflow = $state(0);
	let stageHeight = $state(0);

	/**
	 * While the stage is stuck, the distance it has travelled inside the spacer is
	 * exactly the page scroll it has swallowed — the browser clamps it to
	 * `spacer height - stage height`, i.e. to `overflow`. Feeding it back as
	 * `scrollLeft` turns that vertical scroll into a horizontal one.
	 */
	const sync = (): void => {
		if (!spacer || !stage || !track || overflow <= 0) return;
		const scrolled = stage.getBoundingClientRect().top - spacer.getBoundingClientRect().top;
		track.scrollLeft = Math.min(Math.max(scrolled, 0), overflow);
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
	<h2 class={['text-h3 text-center px-card', content.hideTitle && 'sr-only']}>
		<span class="inline-block rounded-2xl px-8 pt-2 pb-3.5 {colors.pill}">{content.title}</span>
	</h2>
{/snippet}

{#snippet stepContent(step: TimelineStep, index: number)}
	{@const Shape = shapes[index % shapes.length]}
	<div class="relative aspect-square w-full {colors.shape}">
		<Shape class="absolute inset-0 w-full h-full" />
		<div class="absolute inset-0 flex flex-col justify-center px-[15%] {colors.step}">
			<p class="text-lg lg:text-3xl font-bold">{step.title}</p>
			{#if step.shortDesc}
				<p class="text-base lg:text-2xl lg:font-bold mt-2 lg:mt-4 whitespace-pre-line">
					{step.shortDesc}
				</p>
			{/if}
		</div>
	</div>
{/snippet}

{#if steps.length > 0}
	<section aria-label={content.title}>
		<!-- Mobile: a 3-column grid the steps zigzag through, two columns wide each. -->
		<div class="lg:hidden">
			{@render title()}
			<ol class="px-card mt-12 grid grid-cols-3 gap-y-14">
				{#each steps as step, i (i)}
					{@const isLeft = i % 2 === 0}
					{@const Arrow = mobileArrows[i % mobileArrows.length]}
					<li class="relative col-span-2 {isLeft ? 'col-start-1' : 'col-start-2'}">
						{@render stepContent(step, i)}
						{#if i < steps.length - 1}
							<div
								class="absolute top-[85%] w-[38%] {colors.arrow} {isLeft
									? 'left-[80%]'
									: 'right-[80%] -scale-x-100'}"
							>
								<Arrow class="w-full h-auto" />
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
			style={overflow > 0 ? `height: ${stageHeight + overflow}px` : ''}
		>
			<div
				bind:this={stage}
				class="sticky top-32 flex min-h-[calc(100vh-8rem)] flex-col justify-center gap-8"
			>
				{@render title()}
				<ol
					bind:this={track}
					class="grid grid-rows-2 auto-cols-[min(22.5rem,32vh)] gap-x-8 gap-y-16 pl-15 xl:pl-30 pr-15 xl:pr-30 overflow-x-auto scrollbar-none"
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
								<div
									class="absolute left-[72%] w-[52%] pointer-events-none {colors.arrow} {isTop
										? 'top-[62%]'
										: 'bottom-[62%]'}"
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
