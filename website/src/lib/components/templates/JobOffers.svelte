<script lang="ts">
	import { page as appPage } from '$app/state';
	import Img from '$lib/components/ui/Img.svelte';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import JobOfferCard from '$lib/components/ui/JobOfferCard.svelte';
	import ResultsHeader from '$lib/components/ui/ResultsHeader.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import {
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
	let selectedDomains = $state<string[]>(parseListParam(initialParams.get('domains')));
	let selectedCategories = $state<string[]>(parseListParam(initialParams.get('categories')));

	// Only offer terms actually used by at least one job offer, in CMS order.
	const usedSlugs = $derived(
		new Set(page.jobOffers.flatMap((offer) => offer.terms.map((term) => term.slug)))
	);
	const domainTerms = $derived(filterUsedTerms(page.domains, usedSlugs));
	const categoryTerms = $derived(filterUsedTerms(page.jobOfferCategories, usedSlugs));

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activeDomains = $derived(keepKnownSlugs(selectedDomains, domainTerms));
	const activeCategories = $derived(keepKnownSlugs(selectedCategories, categoryTerms));

	const matchesFilters = (offer: JobOffer): boolean =>
		matchesTerms(activeDomains, offer.terms) &&
		matchesTerms(activeCategories, offer.terms) &&
		matchesSearch(search, [offer.title, offer.location, ...offer.terms.map((term) => term.title)]);

	const filteredOffers = $derived(page.jobOffers.filter(matchesFilters));

	const hasSearch = $derived(search.trim() !== '');

	const clearSearch = (): void => {
		search = '';
	};

	// Mirror search + filters into the query string without triggering navigation.
	$effect(() => {
		syncQueryString({ q: search, domains: activeDomains, categories: activeCategories });
	});
</script>

<section aria-labelledby="job-offers-title">
	{#if page.cover}
		<Img
			image={page.cover}
			alt={page.cover.alt ?? page.title}
			sizes="100vw"
			loading="eager"
			fetchpriority="high"
			class="w-full aspect-video md:aspect-3/1 object-cover rounded-3xl mb-18"
		/>
	{/if}

	<h1 id="job-offers-title" class="text-h1 text-blue text-center">{page.title}</h1>
	{#if page.shortDesc}
		<div class="text-body-2 prose text-center max-w-3xl mx-auto mt-6">{@html page.shortDesc}</div>
	{/if}

	<SearchInput
		bind:value={search}
		label="Rechercher une offre"
		placeholder="Rechercher une offre..."
		color="blue"
		class="mt-12 md:mt-18"
	/>

	<FilterTags
		terms={domainTerms}
		bind:selected={selectedDomains}
		legend="Domaines :"
		class="mt-12 md:mt-18"
	/>

	<FilterTags
		terms={categoryTerms}
		bind:selected={selectedCategories}
		legend="Catégories :"
		class="mt-9 md:mt-12"
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
