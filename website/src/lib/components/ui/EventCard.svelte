<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import { termColor } from '$lib/utils/shared';
	import {
		formatEventDate,
		formatEventDateRange,
		formatEventTime,
		timeAttr,
		toDate
	} from '$lib/utils/date';
	import type { AgendaEventCard } from '$lib/interfaces/page';

	interface Props {
		event: AgendaEventCard;
		/** Accent colour of the arrow badge. */
		color?: string;
		headingTag?: string;
		/** Rendered slot width — the card is laid out by its parent, which owns the geometry. */
		sizes?: string;
	}

	let { event, color = 'var(--color-blue)', headingTag = 'h3', sizes }: Props = $props();

	const start = $derived(toDate(event.dateStart));
	// A dateEnd equal to dateStart is a single-day event, not a range.
	const end = $derived(event.dateEnd === event.dateStart ? null : toDate(event.dateEnd));
	const range = $derived(start && end ? formatEventDateRange(start, end) : null);
	const timeStart = $derived(formatEventTime(event.timeStart));
	const timeEnd = $derived(formatEventTime(event.timeEnd));

	// Hover tint: the programme colour over the cover, or equal stripes across them
	// all. Each colour holds flat over most of its band and only blends over a
	// narrow seam, so it reads as stripes rather than as one long gradient.
	const SEAM = 0.5; // share of a band spent fading into the next one
	const programColors = $derived(event.programs.map(termColor));
	const stripes = $derived(
		programColors
			.map((color, i) => {
				const band = 100 / programColors.length;
				const feather = (band * SEAM) / 2;
				// The outer edges of the card stay flush — only interior seams feather.
				const from = i === 0 ? 0 : i * band + feather;
				const to = i === programColors.length - 1 ? 100 : (i + 1) * band - feather;
				return `${color} ${from.toFixed(3)}% ${to.toFixed(3)}%`;
			})
			.join(', ')
	);
	const hoverTint = $derived(
		programColors.length === 0
			? null
			: programColors.length === 1
				? programColors[0]
				: `linear-gradient(135deg, ${stripes})`
	);
</script>

<article class="h-full">
	<a
		href={event.url}
		class="group block relative h-full rounded-2xl overflow-hidden text-white isolate"
	>
		{#if event.cover}
			<Img
				image={event.cover}
				alt={event.cover.alt ?? event.title}
				{sizes}
				class="absolute inset-0 -z-1 w-full h-full object-cover transition-all group-hover:scale-110 group-hover:blur-xs"
			/>
		{/if}
		{#if hoverTint}
			<div
				class="absolute inset-0 -z-1 opacity-0 transition-opacity group-hover:opacity-30"
				style:background={hoverTint}
			></div>
		{/if}
		<div class="absolute inset-0 -z-1 bg-linear-to-b from-black/15 via-black/0 to-black/30 group-hover:opacity-30 transition-opacity"></div>

		<div class="h-full flex flex-col justify-between gap-8 px-5 lg:px-6 pb-5 lg:pb-6 pt-6 lg:pt-8">
			<svelte:element this={headingTag} class="text-h4">{event.title}</svelte:element>
			<div>
				{#if event.shortDesc}
					<div class="prose text-body-2 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity lg:border-b-2 mb-2.5 lg:mb-4 lg:pb-4">
						{@html event.shortDesc}
					</div>
				{/if}
				<div class="flex items-end justify-between gap-4">
					<div class="min-w-0">
						<TermTags terms={event.programs} label="Programmes" size="sm" class="mb-1.5" />
						{#if start}
							<p class="text-body-2">
								{#if range}
									Du <strong><time datetime={event.dateStart}>{range[0]}</time></strong> au
									<strong><time datetime={event.dateEnd}>{range[1]}</time></strong>
								{:else}
									<strong><time datetime={event.dateStart}>{formatEventDate(start)}</time></strong>
								{/if}
							</p>
						{/if}
						<div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-2">
							{#if timeStart}
								<span class="text-label inline-flex items-center gap-1.5">
									<time datetime={timeAttr(event.timeStart)}>{timeStart}</time>
									{#if timeEnd}
										<span aria-hidden="true">–</span>
										<time datetime={timeAttr(event.timeEnd)}>{timeEnd}</time>
									{/if}
								</span>
							{/if}
						</div>
					</div>

					<span
						class="shrink-0 w-12 h-12 rounded-full bg-white flex items-center justify-center"
						style="color: {color}"
					>
						<IconArrow class="w-6.5 h-6.5" />
					</span>
				</div>
			</div>
		</div>
	</a>
</article>
