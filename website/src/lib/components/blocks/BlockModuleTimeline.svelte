<script lang="ts">
	import type { Component } from 'svelte';
	import ArrowStepDown from '$lib/components/svg/ArrowStepDown.svelte';
	import ArrowTimeline1 from '$lib/components/svg/ArrowTimeline1.svelte';
	import ArrowTimeline2 from '$lib/components/svg/ArrowTimeline2.svelte';
	import ArrowTimeline3 from '$lib/components/svg/ArrowTimeline3.svelte';
	import ArrowTimeline4 from '$lib/components/svg/ArrowTimeline4.svelte';
	import ShapeTimeline1 from '$lib/components/svg/ShapeTimeline1.svelte';
	import ShapeTimeline2 from '$lib/components/svg/ShapeTimeline2.svelte';
	import ShapeTimeline3 from '$lib/components/svg/ShapeTimeline3.svelte';
	import ShapeTimeline4 from '$lib/components/svg/ShapeTimeline4.svelte';
	import ShapeTimeline5 from '$lib/components/svg/ShapeTimeline5.svelte';
	import CardTitle from '$lib/components/ui/CardTitle.svelte';
	import { interceptWheel } from '$lib/utils/smoothScroll';
	import type { ModuleTimelineContent, Theme, TimelineStep } from '$lib/interfaces/page';

	interface Props {
		content: ModuleTimelineContent;
		theme?: Theme;
	}

	let { content, theme = 'default' }: Props = $props();

	const steps = $derived(content.steps);

	const palettes = {
		default: {
			card: 'bg-green',
			shape: 'text-green',
			step: 'text-blue',
			pill: 'bg-blue text-white',
			arrow: 'text-pink'
		},
		projets_jeunes: {
			card: 'bg-orange-pale',
			shape: 'text-orange-pale',
			step: 'text-orange',
			pill: 'bg-orange text-white',
			arrow: 'text-orange'
		}
	} as const;

	interface Decoration {
		component: Component<{ class?: string }>;
		class: string;
	}

	/*
	 * Desktop decorations, cycled independently: each step sits on the next shape, and the arrow
	 * after it is the next arrow. Widths are the SVGs' design sizes over 546px, the widest a step
	 * gets (at the 90rem content max), so they match the mockup there and shrink with the step.
	 * Arrows alternate between the upper and lower part of the row; keep an even number of them
	 * so the alternation survives the wrap.
	 */
	const shapes: Decoration[] = [
		{ component: ShapeTimeline1, class: 'w-[73%]' },
		{ component: ShapeTimeline2, class: 'w-[77%]' },
		{ component: ShapeTimeline3, class: 'w-[99%]' },
		{ component: ShapeTimeline4, class: 'w-[80%]' },
		{ component: ShapeTimeline5, class: 'w-[70%]' }
	];

	const arrows: Decoration[] = [
		{ component: ArrowTimeline1, class: 'top-[17%] w-[36.5%]' },
		{ component: ArrowTimeline2, class: 'top-[87%] w-[35%]' },
		{ component: ArrowTimeline3, class: 'top-[14%] w-[33%]' },
		{ component: ArrowTimeline4, class: 'top-[83.5%] w-[30%]' }
	];

	const colors = $derived(theme === 'projets_jeunes' ? palettes.projets_jeunes : palettes.default);

	let section: HTMLElement | undefined = $state();
	let track: HTMLOListElement | undefined = $state();

	/** Horizontal distance the steps can travel; 0 on mobile and for two steps or fewer. */
	let overflow = $state(0);

	/** Where the steps are easing towards, so fast wheel ticks add up instead of reading a mid-animation `scrollLeft`. */
	let trackTarget = 0;

	/** Page scroll swallowed per pixel of horizontal travel: above 1 the steps drift by slower. */
	const PACE = 2;

	/**
	 * Page scroll at which the block rests while the wheel drives the steps: centred in the
	 * space below the fixed header (whose height is the root `scroll-padding-top`), clamped
	 * to what the page can reach when the block sits near either end of it.
	 */
	const restingScroll = (block: HTMLElement): number => {
		const root = document.documentElement;
		const header = parseFloat(getComputedStyle(root).scrollPaddingTop) || 0;
		const { top, height } = block.getBoundingClientRect();
		const offset = Math.max(header, (window.innerHeight + header - height) / 2);
		const maxScroll = root.scrollHeight - window.innerHeight;
		return Math.min(Math.max(top + window.scrollY - offset, 0), maxScroll);
	};

	/**
	 * Takes the wheel only while the page is heading for the block's resting spot and the
	 * steps can still move in the wheel's direction. A tick that would carry the page past
	 * that spot is split: the page scrolls just enough to land on it, the rest moves the
	 * steps. Nothing is reserved in the page flow, so the block keeps its natural height.
	 */
	const onWheel = (deltaY: number, pageTarget: number): number => {
		if (!section || !track || overflow <= 0) return deltaY;

		const forward = deltaY > 0;
		const rest = restingScroll(section);
		const approach = forward ? rest - pageTarget : pageTarget - rest;
		if (approach < -1 || approach > Math.abs(deltaY)) return deltaY;

		const pageStep = Math.max(approach, 0) * Math.sign(deltaY);
		// Arriving from elsewhere: the steps may have been swiped or resized since the last hold.
		if (pageStep !== 0) trackTarget = track.scrollLeft;
		if (forward ? trackTarget >= overflow - 1 : trackTarget <= 1) return deltaY;

		trackTarget = Math.min(Math.max(trackTarget + (deltaY - pageStep) / PACE, 0), overflow);
		track.scrollTo({ left: trackTarget, behavior: 'smooth' });
		return pageStep;
	};

	const measure = (): void => {
		if (!track) return;
		overflow = Math.max(track.scrollWidth - track.clientWidth, 0);
	};

	$effect(() => {
		const el = track;
		if (!el) return;

		measure();
		const observer = new ResizeObserver(measure);
		observer.observe(el);
		const release = interceptWheel(onWheel);

		return () => {
			observer.disconnect();
			release();
		};
	});
</script>

{#snippet stepText(step: TimelineStep)}
	<p class="text-h3">{step.title}</p>
	{#if step.shortDesc}
		<p
			class="mt-4 lg:mt-6 font-bold text-body-1 [&_a]:underline [&_a]:transition-opacity [&_a:hover]:opacity-50"
		>
			<!-- eslint-disable-next-line svelte/no-at-html-tags -- sanitized by the CMS: `Utils::getTimelineSteps()` only lets `<a>` and `<br>` through -->
			{@html step.shortDesc}
		</p>
	{/if}
{/snippet}

{#if steps.length > 0}
	<section bind:this={section} aria-label={content.title} class="max-w-none">
		<div class="flex flex-col gap-12">
			<CardTitle
				title={content.title}
				hideTitle={content.hideTitle}
				class="text-center relative z-2 max-lg:px-5"
				pillClass={colors.pill}
			/>
			<!-- On desktop two steps fill the content column exactly, and each arrow is centred in the gap after its step (`left-[calc(100%+1.125rem)]` is half of `gap-9`). -->
			<ol
				bind:this={track}
				class="flex flex-col items-center px-card-bleed lg:flex-row lg:items-stretch lg:gap-9 lg:overflow-x-auto lg:overflow-y-clip scrollbar-none"
			>
				{#each steps as step, i (i)}
					{@const shape = shapes[i % shapes.length]}
					<!-- `grid-cols-1` pins the column to the step's width, which the shapes' percentage widths resolve against. -->
					<li
						class="relative flex w-full flex-col items-center sm:max-w-100 lg:grid lg:grid-cols-1 lg:place-items-center lg:max-w-none lg:w-[calc((100%-2.25rem)/2)] lg:shrink-0"
					>
						<shape.component
							class="col-start-1 row-start-1 h-auto max-lg:hidden {shape.class} {colors.shape}"
						/>
						<div
							class="w-full rounded-2xl px-6 py-4.5 text-center lg:col-start-1 lg:row-start-1 lg:bg-transparent lg:px-3 lg:py-0 {colors.card} {colors.step}"
						>
							{@render stepText(step)}
						</div>
						{#if i < steps.length - 1}
							{@const arrow = arrows[i % arrows.length]}
							<ArrowStepDown class="-mb-2 h-11 w-auto relative z-1 lg:hidden {colors.arrow}" />
							<arrow.component
								class="absolute left-[calc(100%+1.125rem)] h-auto -translate-1/2 max-lg:hidden {arrow.class} {colors.arrow}"
							/>
						{/if}
					</li>
				{/each}
			</ol>
		</div>
	</section>
{/if}
