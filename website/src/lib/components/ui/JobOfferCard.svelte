<script lang="ts">
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import { toDate } from '$lib/utils/date';
	import type { JobOfferCard } from '$lib/interfaces/jobOffers';

	interface Props {
		offer: JobOfferCard;
		headingTag?: 'h2' | 'h3';
	}

	let { offer, headingTag = 'h2' }: Props = $props();

	const dateFormat = new Intl.DateTimeFormat('fr-CH', { dateStyle: 'long' });

	const deadline = $derived(toDate(offer.deadline));

	// The maximum is only set when the rate is a range.
	const activityRate = $derived(
		offer.activityRateMax === null || offer.activityRateMax === offer.activityRateMin
			? `${offer.activityRateMin}%`
			: `${offer.activityRateMin}% - ${offer.activityRateMax}%`
	);
</script>

<article
	class="relative h-full flex flex-col gap-4 rounded-3xl border-2 border-blue text-blue p-6 md:p-8"
>
	<svelte:element this={headingTag} class="text-h4">
		<a href={offer.url} class="hover:opacity-70 transition after:absolute after:inset-0">
			{offer.title}
		</a>
	</svelte:element>

	<TermTags terms={offer.terms} label="Catégories" />

	<dl class="text-body-2 mt-auto space-y-1">
		<div class="flex gap-2">
			<dt class="text-label">Lieu :</dt>
			<dd>{offer.location}</dd>
		</div>
		<div class="flex gap-2">
			<dt class="text-label">Taux :</dt>
			<dd>{activityRate}</dd>
		</div>
		<div class="flex gap-2">
			<dt class="text-label">Délai :</dt>
			<dd><time datetime={offer.deadline}>{deadline ? dateFormat.format(deadline) : offer.deadline}</time></dd>
		</div>
	</dl>
</article>
