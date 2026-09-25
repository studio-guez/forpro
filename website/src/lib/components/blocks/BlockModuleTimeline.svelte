<script lang="ts">
	import ArrowStepDown from '$lib/components/svg/ArrowStepDown.svelte';
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
			step: 'text-blue',
			pill: 'bg-blue text-white',
			arrow: 'text-pink'
		},
		projets_jeunes: {
			card: 'bg-orange-pale',
			step: 'text-orange',
			pill: 'bg-orange text-white',
			arrow: 'text-orange'
		}
	} as const;

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
			<!-- The desktop gap is the arrow's length minus the same 0.5rem overlap as the mobile arrow; two steps fill the content column exactly. -->
			<ol
				bind:this={track}
				class="flex flex-col items-center px-card-bleed lg:flex-row lg:items-stretch lg:gap-9 lg:overflow-x-auto lg:overflow-y-clip scrollbar-none"
			>
				{#each steps as step, i (i)}
					<li
						class="relative flex w-full flex-col items-center sm:max-w-100 lg:max-w-none lg:w-[calc((100%-2.25rem)/2)] lg:shrink-0 lg:items-stretch"
					>
						<div
							class="w-full rounded-2xl px-6 py-4.5 lg:px-12 lg:py-20 text-center lg:flex lg:grow lg:flex-col lg:justify-center {colors.card} {colors.step}"
						>
							{@render stepText(step)}
						</div>
						{#if i < steps.length - 1}
							<ArrowStepDown
								class="-mb-2 h-11 w-auto relative z-1 lg:absolute lg:mb-0 lg:left-full lg:top-1/2 lg:ml-5.5 lg:-translate-1/2 lg:-rotate-90 {colors.arrow}"
							/>
						{/if}
					</li>
				{/each}
			</ol>
		</div>
	</section>
{/if}
