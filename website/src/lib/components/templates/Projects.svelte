<script lang="ts">
	import { page as appPage } from '$app/state';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import LoadMore from '$lib/components/ui/LoadMore.svelte';
	import ProjectCard from '$lib/components/ui/ProjectCard.svelte';
	import { PAGE, cell, toSizes } from '$lib/utils/imgSizes';
	import ResultsHeader from '$lib/components/ui/ResultsHeader.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import {
		expandSelection,
		filterUsedTerms,
		keepKnownSlugs,
		matchesSearch,
		matchesTerms,
		parseListParam,
		stripTags,
		syncQueryString
	} from '$lib/utils/filters';
	import type { ProjetCard } from '$lib/interfaces/page';
	import type { ProjectsPage } from '$lib/interfaces/project';
	import type { TaxonomyFilterTerm } from '$lib/interfaces/taxonomy';

	let { page }: { page: ProjectsPage } = $props();

	// Card grid: full-width section, 1 / sm:2 / lg:3 columns with a 1.5rem gutter.
	const cardSizes = toSizes(cell(PAGE, { 0: 1, 640: 2, 1024: 3 }, 1.5));

	const color = 'var(--color-orange)';
	const noResultsText = 'Aucun projet ne correspond à votre recherche.';
	// Projects are revealed page by page while scrolling.
	const PAGE_SIZE = 9;

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedPrograms = $state<string[]>(parseListParam(initialParams.get('programs')));
	let selectedCategories = $state<string[]>(parseListParam(initialParams.get('categories')));
	let selectedYears = $state<string[]>(parseListParam(initialParams.get('years')));

	// Only offer terms actually used by at least one project, in CMS order.
	const usedSlugs = $derived(
		new Set(
			page.projects.flatMap((project) =>
				[...project.programs, ...project.categories].map((t) => t.slug)
			)
		)
	);
	const programTerms = $derived(filterUsedTerms(page.programs, usedSlugs));
	const categoryTerms = $derived(filterUsedTerms(page.categories, usedSlugs));
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

	// Selecting a parent term also matches projects tagged with one of its sub-terms.
	const programFilter = $derived(expandSelection(activePrograms, programTerms));
	const categoryFilter = $derived(expandSelection(activeCategories, categoryTerms));

	const matchesFilters = (project: ProjetCard): boolean =>
		matchesTerms(programFilter, project.programs) &&
		matchesTerms(categoryFilter, project.categories) &&
		(activeYears.length === 0 || activeYears.includes(String(project.year))) &&
		matchesSearch(search, [
			project.title,
			stripTags(project.shortDesc),
			project.collectiveName,
			...[...project.programs, ...project.categories].map((term) => term.title)
		]);

	const filteredProjects = $derived(page.projects.filter(matchesFilters));

	let visibleCount = $state(PAGE_SIZE);

	// Any change of filters restarts the pagination at the first page.
	$effect(() => {
		void filteredProjects;
		visibleCount = PAGE_SIZE;
	});

	const visibleProjects = $derived(filteredProjects.slice(0, visibleCount));
	const hasMore = $derived(visibleCount < filteredProjects.length);

	const loadMore = (): void => {
		visibleCount = Math.min(visibleCount + PAGE_SIZE, filteredProjects.length);
	};

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

<section aria-labelledby="projects-title" class="py-12 lg:py-16">
	<h1 id="projects-title" class="text-h1 text-orange text-center">{page.title}</h1>

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
		legend="Projets concernant :"
		class="mt-12 lg:mt-18"
	/>

	<FilterTags
		terms={categoryTerms}
		bind:selected={selectedCategories}
		legend="Catégories :"
		class="mt-9 lg:mt-12"
	/>

	<FilterTags
		terms={yearTerms}
		bind:selected={selectedYears}
		legend="Années :"
		class="mt-9 lg:mt-12"
	/>
</section>

<section aria-label="Projets" class="pb-12 lg:pb-16">
	<div aria-live="polite">
		{#if hasSearch}
			<ResultsHeader
				query={search.trim()}
				count={filteredProjects.length}
				nouns={['projet', 'projets']}
				onClear={clearSearch}
				{noResultsText}
				{color}
			/>
		{:else if filteredProjects.length === 0}
			<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12">
				{noResultsText}
			</p>
		{/if}

		{#if visibleProjects.length > 0}
			<ul class="mt-9 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
				{#each visibleProjects as project (project.url)}
					<li>
						<ProjectCard {project} headingTag="h2" sizes={cardSizes} />
					</li>
				{/each}
			</ul>
		{/if}
	</div>

	<LoadMore {hasMore} {loadMore} label="Voir plus de projets" {color} class="mt-12" />
</section>

<Blocks blocks={page.body} />
