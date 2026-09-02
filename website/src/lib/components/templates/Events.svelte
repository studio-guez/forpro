<script lang="ts">
	import { page as appPage } from '$app/state';
	import BasicHeader from '$lib/components/blocks/BasicHeader.svelte';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import EventCard from '$lib/components/ui/EventCard.svelte';
	import { PAGE, cell, toSizes } from '$lib/utils/imgSizes';
	import EventListItem from '$lib/components/ui/EventListItem.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import LoadMore from '$lib/components/ui/LoadMore.svelte';
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
	// Past events are revealed page by page while scrolling.
	const PAGE_SIZE = 10;

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedPublics = $state<string[]>(parseListParam(initialParams.get('publics')));
	let selectedMonth = $state(initialParams.get('month') ?? '');

	const allEvents = $derived([...page.upcomingEvents, ...page.pastEvents]);

	// Events are only filtered by public; only offer terms actually used by at
	// least one event, in CMS order.
	const usedSlugs = $derived(
		new Set(allEvents.flatMap((event) => event.publics.map((term) => term.slug)))
	);
	const publicTerms = $derived(filterUsedTerms(page.publics, usedSlugs));

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activePublics = $derived(keepKnownSlugs(selectedPublics, publicTerms));

	// Selecting a parent term also matches events tagged with one of its sub-terms.
	const publicFilter = $derived(expandSelection(activePublics, publicTerms));

	const matchesFilters = (event: AgendaEventCard): boolean =>
		matchesTerms(publicFilter, event.publics) &&
		matchesSearch(search, [
			event.title,
			stripTags(event.shortDesc),
			...[...event.programs, ...event.publics].map((term) => term.title)
		]);

	const upcoming = $derived(page.upcomingEvents.filter(matchesFilters));
	const past = $derived(page.pastEvents.filter(matchesFilters));

	/** The months covered by a list of events, in the order the events come in. */
	const monthsOf = (events: AgendaEventCard[]): { value: string; label: string }[] => {
		const seen = new Map<string, string>();
		for (const event of events) {
			const key = monthKey(event.dateStart);
			const date = toDate(event.dateStart);
			if (key && date && !seen.has(key)) seen.set(key, formatMonth(date));
		}
		return [...seen].map(([value, label]) => ({ value, label }));
	};

	// Upcoming events are paginated one month at a time, soonest month first. The
	// selection falls back to the first month, so a filter change that drops the
	// current month lands on a non-empty page.
	const upcomingMonths = $derived(monthsOf(upcoming));
	let selectedUpcomingMonth = $state('');
	const upcomingMonth = $derived(
		upcomingMonths.find((month) => month.value === selectedUpcomingMonth) ?? upcomingMonths[0]
	);
	const upcomingIndex = $derived(
		upcomingMonths.findIndex((month) => month.value === upcomingMonth?.value)
	);
	const upcomingForMonth = $derived(
		upcoming.filter((event) => monthKey(event.dateStart) === upcomingMonth?.value)
	);

	const goToMonth = (offset: number): void => {
		const month = upcomingMonths[upcomingIndex + offset];
		if (month) selectedUpcomingMonth = month.value;
	};

	// Past events are browsed month by month, most recent month first.
	const pastMonths = $derived(monthsOf(past));
	const activeMonth = $derived(
		pastMonths.some((month) => month.value === selectedMonth) ? selectedMonth : ''
	);
	const pastForMonth = $derived(
		activeMonth === '' ? past : past.filter((event) => monthKey(event.dateStart) === activeMonth)
	);

	let visibleCount = $state(PAGE_SIZE);

	// Any change of filters restarts the pagination at the first page.
	$effect(() => {
		void pastForMonth;
		visibleCount = PAGE_SIZE;
	});

	const visiblePast = $derived(pastForMonth.slice(0, visibleCount));
	const hasMore = $derived(visibleCount < pastForMonth.length);

	const loadMore = (): void => {
		visibleCount = Math.min(visibleCount + PAGE_SIZE, pastForMonth.length);
	};

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
			publics: activePublics,
			month: activeMonth
		});
	});
</script>

<BasicHeader title={page.title} {color} id="events-title">
	<SearchInput
		bind:value={search}
		label="Rechercher un événement"
		placeholder="Rechercher un événement..."
		color="blue"
		class="mt-12 lg:mt-18"
	/>

	<FilterTags
		terms={publicTerms}
		bind:selected={selectedPublics}
		legend="Événements concernant :"
		class="mt-12 lg:mt-18"
	/>
</BasicHeader>

<section
	aria-label="Événements à venir"
	style:--events-color={color}
	class="px-base pb-12 lg:pb-16"
>
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

		{#if upcomingMonth}
			<div class="mt-9 border-t-2 border-(--events-color) pt-4">
				<div class="flex items-center gap-2 lg:gap-4">
					<button
						type="button"
						class="text-(--events-color) p-1 disabled:opacity-30"
						aria-label="Mois précédent"
						disabled={upcomingIndex <= 0}
						onclick={() => goToMonth(-1)}
					>
						<IconChevron class="w-6.25 h-6.25 rotate-90" />
					</button>
					<h2 class="text-h3 text-(--events-color)">{upcomingMonth.label}</h2>
					<button
						type="button"
						class="text-(--events-color) p-1 disabled:opacity-30"
						aria-label="Mois suivant"
						disabled={upcomingIndex >= upcomingMonths.length - 1}
						onclick={() => goToMonth(1)}
					>
						<IconChevron class="w-6.25 h-6.25 -rotate-90" />
					</button>
				</div>
				<p class="text-label text-(--events-color) mt-1">
					{upcomingForMonth.length}
					{upcomingForMonth.length > 1 ? 'événements' : 'événement'}
				</p>
			</div>

			<ul class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
				{#each upcomingForMonth as event (event.url)}
					<li class="aspect-3/4">
						<EventCard {event} {color} headingTag="h3" sizes={cardSizes} />
					</li>
				{/each}
			</ul>
		{/if}
	</div>
</section>

{#if past.length > 0}
	<section aria-labelledby="past-events-title" class="px-base pb-12 lg:pb-16">
		<h2 id="past-events-title" class="text-h2 text-center">
			<span class="inline-block bg-blue text-white rounded-full px-8 py-3">
				Les événements passés :
			</span>
		</h2>

		<SelectDropdown
			bind:value={selectedMonth}
			options={pastMonths}
			label="Mois"
			allLabel="Tous les mois"
			{color}
			class="mt-12 lg:mt-18"
		/>

		<div aria-live="polite" class="mt-6">
			{#each visiblePast as event (event.url)}
				<EventListItem {event} {color} headingTag="h3" />
			{/each}
		</div>

		<LoadMore {hasMore} {loadMore} label="Voir plus d'événements" {color} class="mt-12" />
	</section>
{/if}

<Blocks blocks={page.body} />
