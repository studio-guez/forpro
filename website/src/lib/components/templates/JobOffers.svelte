<script lang="ts">
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import CardTitle from '$lib/components/ui/CardTitle.svelte';
	import JobOfferListItem from '$lib/components/ui/JobOfferListItem.svelte';
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import type { JobOffersPage } from '$lib/interfaces/jobOffers';

	let { page }: { page: JobOffersPage } = $props();

	const color = 'var(--color-blue)';
</script>

<PageHeader {page} />

<section aria-labelledby="job-offers-title" class="px-base py-12 lg:py-16">
	<CardTitle
		id="job-offers-title"
		title="Offres d'emploi disponibles"
		hideTitle={true}
		class="text-center"
		pillClass="bg-blue text-white"
	/>

	{#if page.jobOffers.length > 0}
		<div class="mt-9">
			{#each page.jobOffers as offer (offer.url)}
				<JobOfferListItem {offer} {color} headingTag="h3" />
			{/each}
		</div>
	{:else}
		<div class="prose text-body-1 text-grey-dark text-center border-t border-black mt-9 pt-12">
			<!-- eslint-disable-next-line svelte/no-at-html-tags -- rich text comes from the trusted CMS writer field -->
			{@html page.noOffersText}
		</div>
	{/if}
</section>

<Blocks blocks={page.body} />
