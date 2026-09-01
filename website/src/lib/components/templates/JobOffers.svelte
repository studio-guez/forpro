<script lang="ts">
	import { page as appPage } from '$app/state';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import JobOfferCard from '$lib/components/ui/JobOfferCard.svelte';
	import ResultsHeader from '$lib/components/ui/ResultsHeader.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import {
		expandSelection,
		filterUsedTerms,
		keepKnownSlugs,
		matchesSearch,
		matchesTerms,
		parseListParam,
		syncQueryString
	} from '$lib/utils/filters';
	import type { JobOfferCard as JobOffer, JobOffersPage } from '$lib/interfaces/jobOffers';

	let { page }: { page: JobOffersPage } = $props();

	const color = 'var(--color-blue)';
	const noResultsText = 'Aucune offre ne correspond à votre recherche.';

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedSectors = $state<string[]>(parseListParam(initialParams.get('sectors')));

	// Only offer terms actually used by at least one job offer, in CMS order.
	const usedSlugs = $derived(
		new Set(page.jobOffers.flatMap((offer) => offer.terms.map((term) => term.slug)))
	);
	const sectorTerms = $derived(filterUsedTerms(page.sectors, usedSlugs));

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activeSectors = $derived(keepKnownSlugs(selectedSectors, sectorTerms));

	// Selecting a parent term also matches offers tagged with one of its sub-terms.
	const sectorFilter = $derived(expandSelection(activeSectors, sectorTerms));

	const matchesFilters = (offer: JobOffer): boolean =>
		matchesTerms(sectorFilter, offer.terms) &&
		matchesSearch(search, [offer.title, offer.location, ...offer.terms.map((term) => term.title)]);

	const filteredOffers = $derived(page.jobOffers.filter(matchesFilters));

	const hasSearch = $derived(search.trim() !== '');

	const clearSearch = (): void => {
		search = '';
	};

	// Mirror search + filters into the query string without triggering navigation.
	$effect(() => {
		syncQueryString({ q: search, sectors: activeSectors });
	});
</script>

<PageHeader {page} />

<section aria-label="Recherche et filtres">
	<SearchInput
		bind:value={search}
		label="Rechercher une offre"
		placeholder="Rechercher une offre..."
		color="blue"
	/>

	<FilterTags
		terms={sectorTerms}
		bind:selected={selectedSectors}
		legend="Secteurs :"
		class="mt-12 lg:mt-18"
	/>
</section>

<section aria-label="Offres d'emploi">
	<div aria-live="polite">
		{#if hasSearch}
			<ResultsHeader
				query={search.trim()}
				count={filteredOffers.length}
				nouns={['offre', 'offres']}
				onClear={clearSearch}
				{noResultsText}
				{color}
			/>
		{:else if filteredOffers.length === 0}
			<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12">
				{noResultsText}
			</p>
		{/if}

		{#if filteredOffers.length > 0}
			<ul class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
				{#each filteredOffers as offer (offer.url)}
					<li>
						<JobOfferCard {offer} headingTag="h2" />
					</li>
				{/each}
			</ul>
		{/if}
	</div>
</section>

<Blocks blocks={page.body} />
