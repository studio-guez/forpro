<script lang="ts">
	import { page as appPage } from '$app/state';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import LoadMore from '$lib/components/ui/LoadMore.svelte';
	import ProjectCard from '$lib/components/ui/ProjectCard.svelte';
	import ResultsHeader from '$lib/components/ui/ResultsHeader.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import {
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
	import type { TaxonomyTerm } from '$lib/interfaces/taxonomy';

	let { page }: { page: ProjectsPage } = $props();

	const color = 'var(--color-orange)';
	const noResultsText = 'Aucun projet ne correspond à votre recherche.';
	// Projects are revealed page by page while scrolling.
	const PAGE_SIZE = 9;

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedThemes = $state<string[]>(parseListParam(initialParams.get('projectThemes')));
	let selectedTypes = $state<string[]>(parseListParam(initialParams.get('projectTypes')));
	let selectedYears = $state<string[]>(parseListParam(initialParams.get('years')));

	// Only offer terms actually used by at least one project, in CMS order.
	const usedSlugs = $derived(
		new Set(page.projects.flatMap((project) => [...project.themes, ...project.types].map((t) => t.slug)))
	);
	const themeTerms = $derived(filterUsedTerms(page.projectThemes, usedSlugs));
	const typeTerms = $derived(filterUsedTerms(page.projectTypes, usedSlugs));
	// Years are not a taxonomy, but they are filtered with the same tag UI.
	const yearTerms = $derived<TaxonomyTerm[]>(
		page.years.map((year) => ({ slug: String(year), title: String(year), color: 'orange' }))
	);

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activeThemes = $derived(keepKnownSlugs(selectedThemes, themeTerms));
	const activeTypes = $derived(keepKnownSlugs(selectedTypes, typeTerms));
	const activeYears = $derived(keepKnownSlugs(selectedYears, yearTerms));

	const matchesFilters = (project: ProjetCard): boolean =>
		matchesTerms(activeThemes, project.themes) &&
		matchesTerms(activeTypes, project.types) &&
		(activeYears.length === 0 || activeYears.includes(String(project.year))) &&
		matchesSearch(search, [
			project.title,
			stripTags(project.shortDesc),
			project.collectiveName,
			...[...project.themes, ...project.types].map((term) => term.title)
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
			projectThemes: activeThemes,
			projectTypes: activeTypes,
			years: activeYears
		});
	});
</script>

<section aria-labelledby="projects-title" class="py-12 md:py-16">
	<h1 id="projects-title" class="text-h1 text-orange text-center">{page.title}</h1>

	<SearchInput
		bind:value={search}
		label="Rechercher un projet"
		placeholder="Rechercher un projet..."
		color="orange"
		class="mt-12 md:mt-18"
	/>

	<FilterTags
		terms={themeTerms}
		bind:selected={selectedThemes}
		legend="Projets concernant :"
		class="mt-12 md:mt-18"
	/>

	<FilterTags
		terms={typeTerms}
		bind:selected={selectedTypes}
		legend="Types de projet :"
		class="mt-9 md:mt-12"
	/>

	<FilterTags terms={yearTerms} bind:selected={selectedYears} legend="Années :" class="mt-9 md:mt-12" />
</section>

<section aria-label="Projets" class="pb-12 md:pb-16">
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
						<ProjectCard {project} headingTag="h2" />
					</li>
				{/each}
			</ul>
		{/if}
	</div>

	<LoadMore {hasMore} {loadMore} label="Voir plus de projets" {color} class="mt-12" />
</section>

<Blocks blocks={page.body} />
