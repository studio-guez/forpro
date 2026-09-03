<script lang="ts">
	import { page as appPage } from '$app/state';
	import BasicHeader from '$lib/components/blocks/BasicHeader.svelte';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import InfiniteScroll from '$lib/components/ui/InfiniteScroll.svelte';
	import ProjectCard from '$lib/components/ui/ProjectCard.svelte';
	import { PAGE, cell, toSizes } from '$lib/utils/imgSizes';
	import ResultsHeader from '$lib/components/ui/ResultsHeader.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import {
		filterUsedTerms,
		keepKnownSlugs,
		parseListParam,
		syncQueryString
	} from '$lib/utils/filters';
	import { createPaginatedList } from '$lib/utils/paginatedList.svelte';
	import type { ProjetCard } from '$lib/interfaces/page';
	import type { ProjectsList, ProjectsPage } from '$lib/interfaces/project';
	import type { TaxonomyFilterTerm } from '$lib/interfaces/taxonomy';

	let { page }: { page: ProjectsPage } = $props();

	// Card grid: full-width section, 1 / sm:2 / lg:3 columns with a 1.5rem gutter.
	const cardSizes = toSizes(cell(PAGE, { 0: 1, 640: 2, 1024: 3 }, 1.5));

	const color = 'var(--color-orange)';
	const noResultsText = 'Aucun projet ne correspond à votre recherche.';

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedPrograms = $state<string[]>(parseListParam(initialParams.get('programs')));
	let selectedCategories = $state<string[]>(parseListParam(initialParams.get('categories')));
	let selectedYears = $state<string[]>(parseListParam(initialParams.get('years')));

	// Only offer terms actually used by at least one project, in CMS order. The
	// archive is paginated, so which terms it uses is answered by the CMS rather
	// than counted here.
	const programTerms = $derived(filterUsedTerms(page.programs, page.usedPrograms));
	const categoryTerms = $derived(filterUsedTerms(page.categories, page.usedCategories));
	// Years are not a taxonomy, but they are filtered with the same tag UI.
	const yearTerms = $derived<TaxonomyFilterTerm[]>(
		page.years.map((year) => ({
			slug: String(year),
			title: String(year),
			color: 'orange',
			children: []
		}))
	);

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activePrograms = $derived(keepKnownSlugs(selectedPrograms, programTerms));
	const activeCategories = $derived(keepKnownSlugs(selectedCategories, categoryTerms));
	const activeYears = $derived(keepKnownSlugs(selectedYears, yearTerms));

	// The archive is paginated by the CMS, so it is filtered there too — the page
	// payload embeds the first page for the filters in the URL, so a shared or
	// reloaded filtered link renders the right projects server-side. Filters
	// travel raw, exactly as they appear in the URL: the CMS resolves a selected
	// parent term into its sub-terms itself.
	const archive = createPaginatedList<ProjetCard, ProjectsList>({
		kind: 'projects',
		path: () => page.path,
		seed: () => page.projects,
		filters: () => ({
			q: search.trim(),
			programs: activePrograms.join(','),
			categories: activeCategories.join(','),
			years: activeYears.join(',')
		})
	});

	const hasSearch = $derived(search.trim() !== '');

	const clearSearch = (): void => {
		search = '';
	};

	// Mirror search + filters into the query string without triggering navigation.
	$effect(() => {
		syncQueryString({
			q: search,
			programs: activePrograms,
			categories: activeCategories,
			years: activeYears
		});
	});
</script>

<BasicHeader title={page.title} {color} id="projects-title">
	<SearchInput
		bind:value={search}
		label="Rechercher un projet"
		placeholder="Rechercher un projet..."
		color="orange"
		class="mt-12 lg:mt-18"
	/>

	<FilterTags
		terms={programTerms}
		bind:selected={selectedPrograms}
		{color}
		legend="Projets concernant :"
		class="mt-12 lg:mt-18"
	/>

	<FilterTags
		terms={categoryTerms}
		bind:selected={selectedCategories}
		{color}
		legend="Catégories :"
		class="mt-9 lg:mt-12"
	/>

	<FilterTags
		terms={yearTerms}
		bind:selected={selectedYears}
		{color}
		legend="Années :"
		class="mt-9 lg:mt-12"
	/>
</BasicHeader>

<section aria-label="Projets" class="px-base pb-12 lg:pb-16">
	<div aria-live="polite">
		{#if hasSearch}
			<ResultsHeader
				query={search.trim()}
				count={archive.total}
				nouns={['projet', 'projets']}
				onClear={clearSearch}
				{noResultsText}
				{color}
			/>
		{:else if archive.total === 0}
			<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12">
				{noResultsText}
			</p>
		{/if}

		{#if archive.items.length > 0}
			<ul class="mt-9 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
				{#each archive.items as project (project.url)}
					<li>
						<ProjectCard {project} headingTag="h2" sizes={cardSizes} />
					</li>
				{/each}
			</ul>
		{/if}
	</div>

	<InfiniteScroll
		hasMore={archive.hasMore}
		loadMore={archive.loadMore}
		key={archive.items.length}
		{color}
		label="Chargement des projets…"
		class="py-8"
	/>
</section>

<Blocks blocks={page.body} />
