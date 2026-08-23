<script lang="ts">
	import { page as appPage } from '$app/state';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import PageHero from '$lib/components/blocks/PageHero.svelte';
	import PageIntro from '$lib/components/blocks/PageIntro.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import LoadMore from '$lib/components/ui/LoadMore.svelte';
	import MissionCard from '$lib/components/ui/MissionCard.svelte';
	import SelectDropdown from '$lib/components/ui/SelectDropdown.svelte';
	import {
		filterUsedTerms,
		keepKnownSlugs,
		matchesTerms,
		parseListParam,
		syncQueryString
	} from '$lib/utils/filters';
	import type { MissionCard as Mission, MissionsPage } from '$lib/interfaces/missions';

	let { page }: { page: MissionsPage } = $props();

	const color = 'var(--color-blue)';
	const noResultsText = 'Aucune mission ne correspond à votre sélection.';
	// Missions are revealed page by page while scrolling.
	const PAGE_SIZE = 9;

	const sortOptions = [
		{ value: 'dateDesc', label: 'Date (plus récentes)' },
		{ value: 'dateAsc', label: 'Date (plus anciennes)' },
		{ value: 'titleAsc', label: 'Titre (A-Z)' }
	];

	const comparators: Record<string, (a: Mission, b: Mission) => number> = {
		dateDesc: (a, b) => b.date.localeCompare(a.date),
		dateAsc: (a, b) => a.date.localeCompare(b.date),
		titleAsc: (a, b) => a.title.localeCompare(b.title, 'fr')
	};

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let selectedDomains = $state<string[]>(parseListParam(initialParams.get('domains')));
	let sort = $state(initialParams.get('sort') ?? '');

	// Only offer terms actually used by at least one mission, in CMS order.
	const usedSlugs = $derived(
		new Set(page.missions.flatMap((mission) => mission.terms.map((term) => term.slug)))
	);
	const domainTerms = $derived(filterUsedTerms(page.domains, usedSlugs));

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activeDomains = $derived(keepKnownSlugs(selectedDomains, domainTerms));

	const filteredMissions = $derived(
		page.missions.filter((mission) => matchesTerms(activeDomains, mission.terms))
	);

	// An unset sort keeps the order defined in the CMS.
	const sortedMissions = $derived(
		comparators[sort] ? [...filteredMissions].sort(comparators[sort]) : filteredMissions
	);

	let visibleCount = $state(PAGE_SIZE);

	// Any change of filters restarts the pagination at the first page.
	$effect(() => {
		void sortedMissions;
		visibleCount = PAGE_SIZE;
	});

	const visibleMissions = $derived(sortedMissions.slice(0, visibleCount));
	const hasMore = $derived(visibleCount < sortedMissions.length);

	const loadMore = (): void => {
		visibleCount = Math.min(visibleCount + PAGE_SIZE, sortedMissions.length);
	};

	// Mirror filters + sorting into the query string without triggering navigation.
	$effect(() => {
		syncQueryString({ domains: activeDomains, sort });
	});
</script>

<PageHero title={page.title} overtitle={page.overtitle} theme={page.theme} cover={page.cover} />

<PageIntro
	title={page.introTitle}
	text={page.intro}
	parentPage={page.parentPage}
	theme={page.theme}
/>

<section aria-label="Filtres" class="py-12 md:py-16">
	<FilterTags terms={domainTerms} bind:selected={selectedDomains} legend="Missions concernant :" />

	<SelectDropdown
		bind:value={sort}
		options={sortOptions}
		label="Trier par..."
		allLabel="Ordre par défaut"
		{color}
		class="mt-12 md:mt-18"
	/>
</section>

<section aria-label="Missions" class="pb-12 md:pb-16">
	<div aria-live="polite">
		{#if visibleMissions.length > 0}
			<ul class="grid gap-x-6 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
				{#each visibleMissions as mission (mission.url)}
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

	<LoadMore {hasMore} {loadMore} label="Voir plus de missions" {color} class="mt-12" />
</section>

<Blocks blocks={page.body} theme={page.theme} />
