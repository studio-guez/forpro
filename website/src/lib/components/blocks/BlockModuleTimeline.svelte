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
	import ArrowStepDown from '$lib/components/svg/ArrowStepDown.svelte';
	import CardTitle from '$lib/components/ui/CardTitle.svelte';
	import type { ModuleTimelineContent, Theme, TimelineStep } from '$lib/interfaces/page';

	interface Props {
		content: ModuleTimelineContent;
		theme?: Theme;
	}

	let { content, theme = 'default' }: Props = $props();

	const steps = $derived(content.steps);

	const palettes = {
		default: {
			shape: 'text-green',
			card: 'bg-green',
			step: 'text-blue',
			pill: 'bg-blue text-white',
			arrow: 'text-pink'
		},
		projets_jeunes: {
			shape: 'text-orange-pale',
			card: 'bg-orange-pale',
			step: 'text-orange',
			pill: 'bg-orange text-white',
			arrow: 'text-orange'
		}
	} as const;

	const colors = $derived(theme === 'projets_jeunes' ? palettes.projets_jeunes : palettes.default);

	const shapes = [ShapeStep1, ShapeStep2, ShapeStep3, ShapeStep4, ShapeStep5];
	const desktopArrows = [
		ArrowStepDesktop1,
		ArrowStepDesktop2,
		ArrowStepDesktop3,
		ArrowStepDesktop4
	];

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
		// Only the desktop grid overflows; the mobile column measures 0, which leaves the block unpinned.
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

{#snippet stepText(step: TimelineStep, titleClass: string, descClass: string)}
	<p class="font-bold {titleClass}">{step.title}</p>
	{#if step.shortDesc}
		<p
			class="font-bold whitespace-pre-line [&_a]:underline [&_a]:transition-opacity [&_a:hover]:opacity-50 {descClass}"
		>
			<!-- eslint-disable-next-line svelte/no-at-html-tags -- sanitized by the CMS: `<a>` is the only tag `Utils::getLinkedText()` lets through -->
			{@html step.shortDesc}
		</p>
	{/if}
{/snippet}

{#snippet stepCard(step: TimelineStep)}
	<div class="w-full rounded-2xl px-6 py-4.5 text-center lg:hidden {colors.card} {colors.step}">
		{@render stepText(step, 'text-h3', 'text-body-1 mt-4')}
	</div>
{/snippet}

{#snippet stepBlob(step: TimelineStep, index: number)}
	{@const Shape = shapes[index % shapes.length]}
	<div class="relative aspect-square w-full max-lg:hidden {colors.shape}">
		<div class="absolute -inset-1/8">
			<Shape class="w-full h-full" />
		</div>
		<div class="absolute inset-0 flex flex-col justify-center pl-[15%] {colors.step}">
			{@render stepText(step, 'text-h4', 'text-body-1 mt-6')}
		</div>
	</div>
{/snippet}

{#if steps.length > 0}
	<section aria-label={content.title} class="max-w-none">
		<div bind:this={spacer} style={overflow > 0 ? `height: ${stageHeight + runway}px` : ''}>
			<div
				bind:this={stage}
				class="flex flex-col justify-start gap-12 lg:sticky lg:top-27 lg:min-h-[calc(100vh-12rem)] lg:gap-6"
			>
				<CardTitle
					title={content.title}
					hideTitle={content.hideTitle}
					class="text-center relative z-2 max-lg:px-5"
					pillClass={colors.pill}
				/>
				<ol
					bind:this={track}
					style="--step: min(22.5rem, calc(40vh - 120px))"
					class="flex flex-col items-center px-card-bleed lg:grid lg:grid-rows-2 lg:auto-cols-[var(--step)] lg:gap-x-[calc(var(--step)*0.16)] lg:gap-y-[calc(var(--step)*0.18)] lg:py-[calc(var(--step)*0.12)] lg:overflow-x-auto lg:overflow-y-clip scrollbar-none"
				>
					{#each steps as step, i (i)}
						{@const isTop = i % 2 === 0}
						{@const Arrow = desktopArrows[i % desktopArrows.length]}
						<!-- The column is set explicitly so a step never stacks under its neighbour. -->
						<li
							class="relative flex w-full flex-col items-center sm:max-w-100 lg:max-w-none {isTop
								? 'lg:row-start-1'
								: 'lg:row-start-2'}"
							style="grid-column: {i + 1}"
						>
							{@render stepCard(step)}
							{@render stepBlob(step, i)}
							{#if i < steps.length - 1}
								<ArrowStepDown class="-mb-2 h-11 w-auto relative z-1 lg:hidden {colors.arrow}" />
								<div
									class="absolute pointer-events-none max-lg:hidden {colors.arrow} {isTop
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
