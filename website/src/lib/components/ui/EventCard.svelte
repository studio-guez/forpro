<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import { termColor } from '$lib/utils/shared';
	import { formatEventDay, formatTimeRange, toDate } from '$lib/utils/date';
	import type { AgendaEventCard } from '$lib/interfaces/page';

	interface Props {
		event: AgendaEventCard;
		/** Accent colour of the arrow badge. */
		color?: string;
		headingTag?: string;
	}

	let { event, color = 'var(--color-blue)', headingTag = 'h3' }: Props = $props();

	const start = $derived(toDate(event.dateStart, event.timeStart));
	// "14:30 – 17:00" or just "14:30"; null when no time set.
	const time = $derived(formatTimeRange(event.timeStart, event.timeEnd));
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
			sizes="(min-width: 768px) 33vw, 80vw"
			class="absolute inset-0 -z-1 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
		/>
	{/if}
	<div class="absolute inset-0 -z-1 bg-linear-to-b from-black/50 via-black/25 to-black/75"></div>

	<div class="h-full flex flex-col justify-between gap-8 p-6">
		<div>
			<svelte:element this={headingTag} class="text-h4">{event.title}</svelte:element>
			{#if event.shortDesc}
				<div class="prose text-label mt-5 md:hidden">
					{@html event.shortDesc}
				</div>
			{/if}
		</div>

		<div class="flex items-end justify-between gap-4">
			<div class="min-w-0">
				{#if start}
					<p class="text-body-2 font-bold capitalize">
						<time datetime={event.dateStart}>{formatEventDay(start)}</time>
					</p>
				{/if}
				<div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-2">
					{#each event.terms as term (term.slug)}
						<span
							class="text-caption rounded-full px-2.5 py-0.5 leading-tight"
							style="background-color: {termColor(term)}">{term.title}</span
						>
					{/each}
					{#if time}<span class="text-label">{time}</span>{/if}
				</div>
			</div>

			<span
				class="shrink-0 w-12 h-12 rounded-full bg-white flex items-center justify-center transition-transform group-hover:translate-x-1"
				style="color: {color}"
			>
				<IconArrow width={26} height={26} />
			</span>
		</div>
	</div>
</a>
</article>
