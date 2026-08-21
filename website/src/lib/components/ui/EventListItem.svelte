<script lang="ts">
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import { formatShortDate, toDate } from '$lib/utils/date';
	import type { AgendaEventCard } from '$lib/interfaces/page';

	interface Props {
		event: AgendaEventCard;
		/** Accent colour of the row. */
		color?: string;
		detailsLabel?: string;
		headingTag?: string;
	}

	let {
		event,
		color = 'var(--color-blue)',
		detailsLabel = 'Détails',
		headingTag = 'h3'
	}: Props = $props();

	const start = $derived(toDate(event.dateStart));
</script>

<article
	style:--row-color={color}
	class="border-t border-(--row-color) py-4 md:py-6 flex flex-wrap items-center justify-between gap-4"
>
	<div class="min-w-0">
		<svelte:element this={headingTag} class="text-h4 text-(--row-color)">
			<a href={event.url} class="hover:underline">{event.title}</a>
		</svelte:element>
		<div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-2">
			{#if start}
				<p class="text-label text-(--row-color)">
					<time datetime={event.dateStart}>{formatShortDate(start)}</time>
				</p>
				{#if event.terms.length > 0}
					<span class="text-label text-(--row-color)" aria-hidden="true">·</span>
				{/if}
			{/if}
			<TermTags terms={event.terms} label="Thématiques" />
		</div>
	</div>

	<a
		href={event.url}
		class="text-label group shrink-0 flex items-center gap-2 rounded-full border-2 border-(--row-color) text-(--row-color) px-4 py-1.5 leading-tight transition-colors hover:bg-(--row-color) hover:text-white"
	>
		<span>{detailsLabel}<span class="sr-only"> : {event.title}</span></span>
		<IconArrow width={20} height={20} class="transition-transform group-hover:translate-x-1" />
	</a>
</article>
