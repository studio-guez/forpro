<script lang="ts">
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
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
	const terms = $derived(event.programs);

	// The row links to the event, so it is a CTA like any other pill button.
	const cta = $derived({
		label: detailsLabel,
		url: event.url,
		icon: 'arrow',
		target: null
	} as const);
</script>

<article
	style:--row-color={color}
	class="border-t border-(--row-color) py-4 lg:py-6 flex items-center justify-between gap-4"
>
	<div class="min-w-0">
		<svelte:element this={headingTag} class="text-h4 text-(--row-color)">
			{event.title}
		</svelte:element>
		<div class="mt-2.5 flex flex-wrap items-center gap-x-3 gap-y-2">
			{#if start}
				<p class="text-label text-(--row-color)">
					<time datetime={event.dateStart}>{formatShortDate(start)}</time>
				</p>
				{#if terms.length > 0}
					<span class="text-label text-(--row-color)" aria-hidden="true">·</span>
				{/if}
			{/if}
			<TermTags {terms} label="Thématiques" size="md" />
		</div>
	</div>

	<CtaLink {cta} {color} ariaLabel="{detailsLabel} : {event.title}" class="shrink-0" />
</article>
