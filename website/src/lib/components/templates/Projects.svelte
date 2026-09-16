<script lang="ts">
	import { page as appPage } from '$app/state';
	import BasicHeader from '$lib/components/blocks/BasicHeader.svelte';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import FilterDropdown from '$lib/components/ui/FilterDropdown.svelte';
	import InfiniteScroll from '$lib/components/ui/InfiniteScroll.svelte';
	import ListHeader from '$lib/components/ui/ListHeader.svelte';
	import ListToolbar from '$lib/components/ui/ListToolbar.svelte';
	import ProjectCard from '$lib/components/ui/ProjectCard.svelte';
	import { PAGE, cell, toSizes } from '$lib/utils/imgSizes';
	import ResultsHeader from '$lib/components/ui/ResultsHeader.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import SelectDropdown from '$lib/components/ui/SelectDropdown.svelte';
	import {
		filterUsedTerms,
		keepKnownSlugs,
		parseListParam,
		syncQueryString
	} from '$lib/utils/filters';
	import { createPaginatedList } from '$lib/utils/paginatedList.svelte';
	import type { ProjetCard } from '$lib/interfaces/page';
	import type { ProjectsList, ProjectsPage } from '$lib/interfaces/project';

	let { page }: { page: ProjectsPage } = $props();

	const cardSizes = toSizes(cell(PAGE, { 0: 1, 640: 2, 1024: 3 }, 1.5));

	const color = 'var(--color-orange)';

	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedPrograms = $state<string[]>(parseListParam(initialParams.get('programs')));
	let selectedYear = $state(initialParams.get('years') ?? '');

	const hasSearch = $derived(search.trim() !== '');

	const programTerms = $derived(filterUsedTerms(page.programs, page.usedPrograms));

	const activePrograms = $derived(keepKnownSlugs(selectedPrograms, programTerms));

	const yearOptions = $derived(
		page.years.map((year) => ({ value: String(year), label: String(year) }))
	);

	// The year steps aside while a search runs: its band gives way to the results header, and a filter the visitor cannot see must not narrow them.
	const activeYear = $derived(
		!hasSearch && yearOptions.some((option) => option.value === selectedYear) ? selectedYear : ''
	);

	const archive = createPaginatedList<ProjetCard, ProjectsList>({
		kind: 'projects',
		path: () => page.path,
		seed: () => page.projects,
		filters: () => ({
			q: search.trim(),
			programs: activePrograms.join(','),
			years: activeYear
		})
	});

	$effect(() => {
		syncQueryString({
			q: search,
			programs: activePrograms,
			years: activeYear
		});
	});
</script>

<BasicHeader title={page.title} {color} id="projects-title"></BasicHeader>

<section aria-label="Projets" class="px-base pb-12 lg:pb-16">
	<ListToolbar {color}>
		<FilterDropdown
			terms={programTerms}
			bind:selected={selectedPrograms}
			label="Ressources"
			{color}
		/>
		{#if yearOptions.length > 0}
			<SelectDropdown
				bind:value={selectedYear}
				options={yearOptions}
				label="Années"
				allLabel="Toutes les années"
				{color}
			/>
		{/if}

		{#snippet end()}
			<SearchInput
				bind:value={search}
				label="Rechercher un projet"
				placeholder="Rechercher un projet"
				color="orange"
			/>
		{/snippet}
	</ListToolbar>

	{#if hasSearch}
		<div aria-live="polite">
			<ResultsHeader
				query={search.trim()}
				count={archive.total}
				nouns={['projet', 'projets']}
				noResultsText={page.noResultsText}
				{color}
				variant="section"
				rule={false}
			/>
		</div>
	{:else}
		<div aria-live="polite">
			<ListHeader {color} count={archive.total} nouns={['projet', 'projets']} rule={false}>
				<h2 class="sr-only md:not-sr-only text-h2 text-(--list-color) lg:h-15">Tous les projets</h2>

				{#if archive.total === 0}
					<!-- eslint-disable-next-line svelte/no-at-html-tags -- rich text comes from the trusted CMS writer field -->
					<div class="prose text-body-1 text-(--list-color) mt-2.5">{@html page.noResultsText}</div>
				{/if}
			</ListHeader>
		</div>
	{/if}

	{#if archive.items.length > 0}
		<ul class="mt-9 grid max-sm:gap-y-12 gap-6 sm:grid-cols-2 lg:grid-cols-3">
			{#each archive.items as project (project.url)}
				<li>
					<ProjectCard {project} headingTag="h3" variant="inverted" sizes={cardSizes} />
				</li>
			{/each}
		</ul>
	{/if}

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
