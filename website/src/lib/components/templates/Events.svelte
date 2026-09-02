<script lang="ts">
	import { page as appPage } from '$app/state';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import EventCard from '$lib/components/ui/EventCard.svelte';
	import { PAGE, cell, toSizes } from '$lib/utils/imgSizes';
	import EventListItem from '$lib/components/ui/EventListItem.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import ResultsHeader from '$lib/components/ui/ResultsHeader.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import SelectDropdown from '$lib/components/ui/SelectDropdown.svelte';
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
	import { formatMonth, monthKey, toDate } from '$lib/utils/date';
	import type { AgendaEventCard } from '$lib/interfaces/page';
	import type { EventsPage } from '$lib/interfaces/event';

	let { page }: { page: EventsPage } = $props();

	// Card grid: full-width section, 1 / sm:2 / lg:3 columns with a 1.5rem gutter.
	const cardSizes = toSizes(cell(PAGE, { 0: 1, 640: 2, 1024: 3 }, 1.5));

	const color = 'var(--color-blue)';
	const noResultsText = 'Aucun événement ne correspond à votre recherche.';

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedPrograms = $state<string[]>(parseListParam(initialParams.get('programs')));
	let selectedPublics = $state<string[]>(parseListParam(initialParams.get('publics')));
	let selectedMonth = $state(initialParams.get('month') ?? '');

	const allEvents = $derived([...page.upcomingEvents, ...page.pastEvents]);

	// Only offer terms actually used by at least one event, in CMS order.
	const usedSlugs = $derived(new Set(allEvents.flatMap((event) => event.terms.map((t) => t.slug))));
	const programTerms = $derived(filterUsedTerms(page.programs, usedSlugs));
	const publicTerms = $derived(filterUsedTerms(page.publics, usedSlugs));

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activePrograms = $derived(keepKnownSlugs(selectedPrograms, programTerms));
	const activePublics = $derived(keepKnownSlugs(selectedPublics, publicTerms));

	// Selecting a parent term also matches events tagged with one of its sub-terms.
	const programFilter = $derived(expandSelection(activePrograms, programTerms));
	const publicFilter = $derived(expandSelection(activePublics, publicTerms));

	const matchesFilters = (event: AgendaEventCard): boolean =>
		matchesTerms(programFilter, event.terms) &&
		matchesTerms(publicFilter, event.terms) &&
		matchesSearch(search, [
			event.title,
			stripTags(event.shortDesc),
			...event.terms.map((t) => t.title)
		]);

	const upcoming = $derived(page.upcomingEvents.filter(matchesFilters));
	const past = $derived(page.pastEvents.filter(matchesFilters));

	// Past events are browsed month by month, most recent month first.
	const months = $derived.by(() => {
		const seen = new Map<string, string>();
		for (const event of past) {
			const key = monthKey(event.dateStart);
			const date = toDate(event.dateStart);
			if (key && date && !seen.has(key)) seen.set(key, formatMonth(date));
		}
		return [...seen].map(([value, label]) => ({ value, label }));
	});

	const activeMonth = $derived(months.some((m) => m.value === selectedMonth) ? selectedMonth : '');
	const pastForMonth = $derived(
		activeMonth === '' ? past : past.filter((event) => monthKey(event.dateStart) === activeMonth)
	);

	const hasSearch = $derived(search.trim() !== '');
	// Past events are listed further down, so they count as results too.
	const resultCount = $derived(upcoming.length + past.length);

	const clearSearch = (): void => {
		search = '';
	};

	// Mirror search + filters into the query string without triggering navigation.
	$effect(() => {
		syncQueryString({
			q: search,
			programs: activePrograms,
			publics: activePublics,
			month: activeMonth
		});
	});
</script>

<section aria-labelledby="events-title" class="py-12 lg:py-16">
	<h1 id="events-title" class="text-h1 text-blue text-center">{page.title}</h1>

	<SearchInput
		bind:value={search}
		label="Rechercher un événement"
		placeholder="Rechercher un événement..."
		color="blue"
		class="mt-12 lg:mt-18"
	/>

	<FilterTags
		terms={programTerms}
		bind:selected={selectedPrograms}
		legend="Événements concernant :"
		class="mt-12 lg:mt-18"
	/>

	<FilterTags
		terms={publicTerms}
		bind:selected={selectedPublics}
		legend="Publics :"
		class="mt-9 lg:mt-12"
	/>
</section>

<section aria-label="Événements à venir" class="pb-12 lg:pb-16">
	<div aria-live="polite">
		{#if hasSearch}
			<ResultsHeader
				query={search.trim()}
				count={resultCount}
				nouns={['événement', 'événements']}
				onClear={clearSearch}
				{noResultsText}
				{color}
			/>
			{#if upcoming.length === 0 && past.length > 0}
				<p class="text-body-1 text-grey-dark mt-4">
					Aucun événement à venir, voir les événements passés ci-dessous.
				</p>
			{/if}
		{:else if upcoming.length === 0}
			<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12">
				Aucun événement à venir pour le moment.
			</p>
		{/if}

		{#if upcoming.length > 0}
			<ul class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
				{#each upcoming as event (event.url)}
					<li class="aspect-3/4">
						<EventCard {event} {color} headingTag="h2" sizes={cardSizes} />
					</li>
				{/each}
			</ul>
		{/if}
	</div>
</section>

{#if past.length > 0}
	<section aria-labelledby="past-events-title" class="pb-12 lg:pb-16">
		<h2 id="past-events-title" class="text-h2 text-center">
			<span class="inline-block bg-blue text-white rounded-full px-8 py-3">
				Les événements passés :
			</span>
		</h2>

		<SelectDropdown
			bind:value={selectedMonth}
			options={months}
			label="Mois"
			allLabel="Tous les mois"
			{color}
			class="mt-12 lg:mt-18"
		/>

		<div aria-live="polite" class="mt-6">
			{#each pastForMonth as event (event.url)}
				<EventListItem {event} {color} headingTag="h3" />
			{/each}
		</div>
	</section>
{/if}

<Blocks blocks={page.body} />
