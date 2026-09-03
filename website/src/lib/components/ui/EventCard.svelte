<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
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
				class="absolute inset-0 -z-1 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
			/>
		{/if}
		<div class="absolute inset-0 -z-1 bg-linear-to-b from-black/50 via-black/25 to-black/75"></div>

		<div class="h-full flex flex-col justify-between gap-8 p-6">
			<div>
				<svelte:element this={headingTag} class="text-h4">{event.title}</svelte:element>
				{#if event.shortDesc}
					<div class="prose text-label mt-5 lg:hidden">
						{@html event.shortDesc}
					</div>
				{/if}
			</div>

			<div class="flex items-end justify-between gap-4">
				<div class="min-w-0">
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
					<div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-2">
						<TermTags terms={event.programs} label="Programmes" size="sm" />
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
	</a>
</article>
