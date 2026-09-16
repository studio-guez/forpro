<script lang="ts">
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import { formatShortDate, toDate } from '$lib/utils/date';
	import type { JobOfferCard } from '$lib/interfaces/jobOffers';

	interface Props {
		offer: JobOfferCard;
		/** Accent colour of the row. */
		color?: string;
		applyLabel?: string;
		headingTag?: string;
	}

	let {
		offer,
		color = 'var(--color-blue)',
		applyLabel = 'Postuler',
		headingTag = 'h3'
	}: Props = $props();

	const published = $derived(toDate(offer.publishedDate));

	const cta = $derived({
		label: applyLabel,
		url: offer.url,
		icon: 'arrow',
		target: null
	} as const);
</script>

<article style:--row-color={color} class="border-t-2 border-(--row-color) py-3 lg:pt-4 lg:pb-6">
	<svelte:element this={headingTag} class="text-h4 text-(--row-color)">
		{offer.title}
	</svelte:element>

	{#if offer.sector || offer.publishedDate}
		<div class="mt-2.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-body-2 text-(--row-color)">
			{#if offer.sector}
				<p>{offer.sector}</p>
				{#if offer.publishedDate}
					<span aria-hidden="true">·</span>
				{/if}
			{/if}
			{#if offer.publishedDate}
				<p>
					Posté le
					<time datetime={offer.publishedDate}>
						{published ? formatShortDate(published) : offer.publishedDate}
					</time>
				</p>
			{/if}
		</div>
	{/if}

	<div class="mt-3 flex justify-end">
		<CtaLink {cta} {color} ariaLabel="{applyLabel} : {offer.title}" />
	</div>
</article>
