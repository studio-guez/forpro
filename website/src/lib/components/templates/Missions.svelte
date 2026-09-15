<script lang="ts">
	import { page as appPage } from '$app/state';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import FilterDropdown from '$lib/components/ui/FilterDropdown.svelte';
	import InfiniteScroll from '$lib/components/ui/InfiniteScroll.svelte';
	import ListToolbar from '$lib/components/ui/ListToolbar.svelte';
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

	// An unset sort is the CMS default (most recently published first), so that is the empty option rather than a `dateDesc` of its own.
	const sortOptions = [
		{ value: '', label: 'Date (plus récentes)' },
		{ value: 'dateAsc', label: 'Date (plus anciennes)' },
		{ value: 'titleAsc', label: 'Titre (A-Z)' }
	];

	const initialParams = appPage.url.searchParams;
	let selectedCategories = $state<string[]>(parseListParam(initialParams.get('categories')));
	// `?sort=dateDesc` is what the empty option already does, so it maps onto that option instead of none.
	const initialSort = initialParams.get('sort') ?? '';
	let sort = $state(initialSort === 'dateDesc' ? '' : initialSort);

	const categoryTerms = $derived(filterUsedTerms(page.categories, page.usedCategories));

	const activeCategories = $derived(keepKnownSlugs(selectedCategories, categoryTerms));

	// Filtered and sorted by the CMS: the sort decides which missions land in a page at all.
	const list = createPaginatedList<Mission, MissionsList>({
		kind: 'missions',
		path: () => page.path,
		seed: () => page.missions,
		filters: () => ({ categories: activeCategories.join(','), sort })
	});

	$effect(() => {
		syncQueryString({ categories: activeCategories, sort });
	});
</script>

<PageHeader {page} />

<section aria-label="Missions" class="px-base py-12 lg:py-16">
	<ListToolbar {color}>
		<SelectDropdown
			bind:value={sort}
			options={sortOptions}
			label="Trier par..."
			clearable={false}
			{color}
		/>
		<FilterDropdown
			terms={categoryTerms}
			bind:selected={selectedCategories}
			label="Catégories"
			{color}
		/>
	</ListToolbar>

	<p class="sr-only" aria-live="polite">
		{list.total}
		{list.total > 1 ? 'missions' : 'mission'}
	</p>

	{#if list.items.length > 0}
		<ul class="mt-12 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
			{#each list.items as mission (mission.url)}
				<li>
					<MissionCard {mission} headingTag="h2" />
				</li>
			{/each}
		</ul>
	{:else}
		<p class="text-body-1 text-black text-center border-t border-black pt-12 mt-12">
			{noResultsText}
		</p>
	{/if}

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
