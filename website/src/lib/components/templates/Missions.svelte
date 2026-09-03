<script lang="ts">
	import { page as appPage } from '$app/state';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import InfiniteScroll from '$lib/components/ui/InfiniteScroll.svelte';
	import MissionCard from '$lib/components/ui/MissionCard.svelte';
	import SelectDropdown from '$lib/components/ui/SelectDropdown.svelte';
	import {
		filterUsedTerms,
		keepKnownSlugs,
		parseListParam,
		syncQueryString
	} from '$lib/utils/filters';
	import { createPaginatedList } from '$lib/utils/paginatedList.svelte';
	import type {
		MissionCard as Mission,
		MissionsList,
		MissionsPage
	} from '$lib/interfaces/missions';

	let { page }: { page: MissionsPage } = $props();

	const color = 'var(--color-blue)';
	const noResultsText = 'Aucune mission ne correspond à votre sélection.';

	// The values the CMS sorts on; an unset sort keeps the CMS order.
	const sortOptions = [
		{ value: 'dateDesc', label: 'Date (plus récentes)' },
		{ value: 'dateAsc', label: 'Date (plus anciennes)' },
		{ value: 'titleAsc', label: 'Titre (A-Z)' }
	];

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let selectedCategories = $state<string[]>(parseListParam(initialParams.get('categories')));
	let sort = $state(initialParams.get('sort') ?? '');

	// Only offer terms actually used by at least one mission, in CMS order. The
	// list is paginated, so which terms it uses is answered by the CMS rather
	// than counted here.
	const categoryTerms = $derived(filterUsedTerms(page.categories, page.usedCategories));

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activeCategories = $derived(keepKnownSlugs(selectedCategories, categoryTerms));

	// The list is paginated by the CMS, so it is filtered *and sorted* there too:
	// the sort decides which missions land in a page at all. The page payload
	// embeds the first page for the filters in the URL, so a shared or reloaded
	// filtered link renders the right missions server-side. Filters travel raw,
	// exactly as they appear in the URL: the CMS resolves a selected parent term
	// into its sub-terms itself.
	const list = createPaginatedList<Mission, MissionsList>({
		kind: 'missions',
		path: () => page.path,
		seed: () => page.missions,
		filters: () => ({ categories: activeCategories.join(','), sort })
	});

	// Mirror filters + sorting into the query string without triggering navigation.
	$effect(() => {
		syncQueryString({ categories: activeCategories, sort });
	});
</script>

<PageHeader {page} />

<section aria-label="Filtres" class="px-base py-12 lg:py-16">
	<FilterTags
		terms={categoryTerms}
		bind:selected={selectedCategories}
		{color}
		legend="Missions concernant :"
	/>

	<SelectDropdown
		bind:value={sort}
		options={sortOptions}
		label="Trier par..."
		allLabel="Ordre par défaut"
		{color}
		class="mt-12 lg:mt-18"
	/>
</section>

<section aria-label="Missions" class="px-base pb-12 lg:pb-16">
	<div aria-live="polite">
		{#if list.items.length > 0}
			<ul class="grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
				{#each list.items as mission (mission.url)}
					<li>
						<MissionCard {mission} headingTag="h2" />
					</li>
				{/each}
			</ul>
		{:else}
			<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12">
				{noResultsText}
			</p>
		{/if}
	</div>

	<InfiniteScroll
		hasMore={list.hasMore}
		loadMore={list.loadMore}
		key={list.items.length}
		{color}
		label="Chargement des missions…"
		class="py-8"
	/>
</section>

<Blocks blocks={page.body} theme={page.theme} />
