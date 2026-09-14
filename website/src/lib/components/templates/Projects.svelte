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

	// Card grid: full-width section, 1 / sm:2 / lg:3 columns with a 1.5rem gutter.
	const cardSizes = toSizes(cell(PAGE, { 0: 1, 640: 2, 1024: 3 }, 1.5));

	const color = 'var(--color-orange)';
	const noResultsText = 'Aucun projet ne correspond à votre recherche.';

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedPrograms = $state<string[]>(parseListParam(initialParams.get('programs')));
	let selectedYear = $state(initialParams.get('years') ?? '');

	const hasSearch = $derived(search.trim() !== '');

	// Only offer terms actually used by at least one project, in CMS order. The
	// archive is paginated, so which terms it uses is answered by the CMS rather
	// than counted here.
	const programTerms = $derived(filterUsedTerms(page.programs, page.usedPrograms));

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activePrograms = $derived(keepKnownSlugs(selectedPrograms, programTerms));

	// Years are not a taxonomy and only one is browsed at a time, so they are
	// picked in the archive band rather than tagged in the header.
	// A project with no year is stored as 0, which is not a year to offer.
	const yearOptions = $derived(
		page.years
			.filter((year) => year > 0)
			.map((year) => ({ value: String(year), label: String(year) }))
	);

	// A year coming from the URL that no project uses is dropped, so the counter
	// stays accurate. A search is answered across the whole archive, so the year
	// steps aside while one runs — the band it is picked in gives way to the
	// results header, and a filter the visitor cannot see must not narrow them
	// down. It comes back as soon as the search is cleared.
	const activeYear = $derived(
		!hasSearch && yearOptions.some((option) => option.value === selectedYear) ? selectedYear : ''
	);

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
			years: activeYear
		})
	});

	// Mirror search + filters into the query string without triggering navigation.
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
	<ListToolbar>
		<FilterDropdown
			terms={programTerms}
			bind:selected={selectedPrograms}
			label="Projets concernant"
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
				{noResultsText}
				{color}
				variant="section"
				rule={false}
			/>
		</div>
	{:else}
		<!-- The band the archive is browsed with, without a rule: the toolbar already
		     parts it from the header. Only the count is announced. -->
		<ListHeader {color} rule={false}>
			<h2 class="text-h2 text-(--list-color)">Tous les projets</h2>

			<div class="mt-2 flex flex-wrap items-center gap-x-6 gap-y-3">
				{#if archive.total > 0}
					<p class="text-label text-(--list-color)" aria-live="polite">
						{archive.total}
						{archive.total > 1 ? 'projets' : 'projet'}
					</p>
				{/if}
			</div>

			{#if archive.total === 0}
				<p class="text-body-1 text-(--list-color) mt-4" aria-live="polite">{noResultsText}</p>
			{/if}
		</ListHeader>
	{/if}

	{#if archive.items.length > 0}
		<ul class="mt-9 grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
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
