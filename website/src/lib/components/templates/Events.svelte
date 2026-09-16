<script lang="ts">
	import { page as appPage } from '$app/state';
	import { fly } from 'svelte/transition';
	import { prefersReducedMotion } from 'svelte/motion';
	import { MediaQuery } from 'svelte/reactivity';
	import BasicHeader from '$lib/components/blocks/BasicHeader.svelte';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import CardTitle from '$lib/components/ui/CardTitle.svelte';
	import EventCard from '$lib/components/ui/EventCard.svelte';
	import { PAGE, cell, toSizes } from '$lib/utils/imgSizes';
	import EventListItem from '$lib/components/ui/EventListItem.svelte';
	import FilterDropdown from '$lib/components/ui/FilterDropdown.svelte';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import IconHamburger from '$lib/components/svg/IconHamburger.svelte';
	import InfiniteScroll from '$lib/components/ui/InfiniteScroll.svelte';
	import ListHeader from '$lib/components/ui/ListHeader.svelte';
	import ListToolbar from '$lib/components/ui/ListToolbar.svelte';
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
		syncQueryString
	} from '$lib/utils/filters';
	import { createPaginatedList } from '$lib/utils/paginatedList.svelte';
	import { CTA_BASE, ctaColorClasses } from '$lib/utils/ctaStyles';
	import { monthKey, monthKeyLabel, monthKeyOf, monthKeysBetween } from '$lib/utils/date';
	import type { AgendaEventCard } from '$lib/interfaces/page';
	import type { EventsPage, PastEventsList } from '$lib/interfaces/event';

	let { page }: { page: EventsPage } = $props();

	const cardSizes = toSizes(cell(PAGE, { 0: 1, 640: 2, 1024: 3 }, 1.5));

	const color = 'var(--color-blue)';

	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedPublics = $state<string[]>(parseListParam(initialParams.get('publics')));
	// `publics` and `q` above only narrow upcoming events: the past archive has a search of its own.
	let pastSearch = $state(initialParams.get('pastQ') ?? '');
	let selectedMonth = $state(initialParams.get('month') ?? '');

	type UpcomingView = 'month' | 'all';
	let upcomingView = $state<UpcomingView>(initialParams.get('view') === 'month' ? 'month' : 'all');

	// The month view only exists from the toolbar breakpoint up, but the server assumes a wide screen so a shared `?view=month` link renders server-side.
	const isWide = new MediaQuery('(width >= 48rem)', true);
	const showAllUpcoming = $derived(!isWide.current || upcomingView === 'all');

	const toggleUpcomingView = (): void => {
		upcomingView = upcomingView === 'all' ? 'month' : 'all';
	};

	const viewToggleClasses = `${CTA_BASE} ${ctaColorClasses(false)} text-sm gap-2 px-3.5 h-10 border-3 shrink-0`;

	const publicTerms = $derived(filterUsedTerms(page.publics, page.usedPublics));

	const activePublics = $derived(keepKnownSlugs(selectedPublics, publicTerms));

	const publicFilter = $derived(expandSelection(activePublics, publicTerms));

	// Titles only, by design: descriptions and the dropdown's terms are not searched.
	const matchesFilters = (event: AgendaEventCard): boolean =>
		matchesTerms(publicFilter, event.publics) && matchesSearch(search, [event.title]);

	const upcoming = $derived(page.upcomingEvents.filter(matchesFilters));

	// Months already over are never offered: a February–October event is listed from the current month on.
	const currentMonth = monthKeyOf(new Date());

	/** Every month an upcoming event runs in, from the current one at the earliest. */
	const eventMonths = (event: AgendaEventCard): string[] => {
		const start = monthKey(event.dateStart);
		if (!start) return [];

		const end = monthKey(event.dateEnd) ?? start;
		return monthKeysBetween(start < currentMonth ? currentMonth : start, end < start ? start : end);
	};

	// Falls back to the first month so a filter change that drops the current month lands on a non-empty page.
	const upcomingMonths = $derived(
		[...new Set(upcoming.flatMap(eventMonths))]
			.sort()
			.map((value) => ({ value, label: monthKeyLabel(value) }))
	);
	let selectedUpcomingMonth = $state('');
	const upcomingMonth = $derived(
		upcomingMonths.find((month) => month.value === selectedUpcomingMonth) ?? upcomingMonths[0]
	);
	const upcomingIndex = $derived(
		upcomingMonths.findIndex((month) => month.value === upcomingMonth?.value)
	);
	const upcomingForMonth = $derived(
		upcoming.filter((event) => eventMonths(event).includes(upcomingMonth?.value ?? ''))
	);

	let slideDirection = $state(1);
	const monthSlideDuration = $derived(prefersReducedMotion.current ? 0 : 500);
	const monthEnter = $derived({
		duration: monthSlideDuration,
		x: `${100 * slideDirection}%`
	});
	const monthLeave = $derived({
		duration: monthSlideDuration,
		x: `${-100 * slideDirection}%`
	});

	const goToMonth = (offset: number): void => {
		const month = upcomingMonths[upcomingIndex + offset];
		if (!month) return;

		slideDirection = offset;
		selectedUpcomingMonth = month.value;
	};

	// The archive search is `pastQ` in the URL, sent to the archive route as its `q`; the agenda's `q` is not an archive filter.
	const archive = createPaginatedList<AgendaEventCard, PastEventsList>({
		kind: 'past-events',
		path: () => page.path,
		seed: () => page.pastEvents,
		filters: () => ({
			q: pastSearch.trim(),
			month: activeMonth
		})
	});

	// Annotated: the months close a type cycle (archive → activeMonth → months).
	const pastMonths: { value: string; label: string }[] = $derived(
		archive.current.months.map((value) => ({ value, label: monthKeyLabel(value) }))
	);
	const activeMonth = $derived(
		pastMonths.some((month) => month.value === selectedMonth) ? selectedMonth : ''
	);

	const hasSearch = $derived(search.trim() !== '');

	const visibleUpcoming = $derived(hasSearch || showAllUpcoming ? upcoming : upcomingForMonth);
	const announcedCount = $derived(visibleUpcoming.length);

	$effect(() => {
		syncQueryString({
			q: search,
			publics: activePublics,
			pastQ: pastSearch,
			month: activeMonth,
			view: upcomingView === 'all' ? '' : 'month'
		});
	});
</script>

{#snippet viewToggle()}
	<div class="hidden md:contents">
		<button
			type="button"
			style:--color-cta="var(--list-color)"
			class={viewToggleClasses}
			aria-pressed={showAllUpcoming}
			onclick={toggleUpcomingView}
		>
			<span class="text-trim">{showAllUpcoming ? 'Vue par mois' : 'Tous les événements'}</span>
			<IconHamburger class="w-6 h-6" />
		</button>
	</div>
{/snippet}

<BasicHeader title={page.title} {color} id="events-title"></BasicHeader>

<section aria-label="Événements à venir" class="px-base pb-12 lg:pb-16">
	<ListToolbar {color}>
		<FilterDropdown
			terms={publicTerms}
			bind:selected={selectedPublics}
			label="Événements concernant"
			{color}
		/>

		{#snippet end()}
			<SearchInput
				bind:value={search}
				label="Rechercher un événement"
				placeholder="Rechercher un événement"
				color="blue"
			/>
		{/snippet}
	</ListToolbar>

	<p class="sr-only" aria-live="polite">
		{announcedCount}
		{announcedCount > 1 ? 'événements' : 'événement'}
	</p>

	{#if hasSearch}
		<ResultsHeader
			query={search.trim()}
			count={upcoming.length}
			nouns={['événement', 'événements']}
			noResultsText={page.noResultsText}
			{color}
			variant="section"
			rule={false}
		/>
	{:else if upcoming.length === 0}
		<div class="prose text-body-1 text-grey-dark text-center border-t border-black pt-12">
			<!-- eslint-disable-next-line svelte/no-at-html-tags -- rich text comes from the trusted CMS writer field -->
			{@html page.noUpcomingText}
		</div>
	{:else if showAllUpcoming}
		<ListHeader {color} count={upcoming.length} nouns={['événement', 'événements']} rule={false}>
			<div class="flex flex-wrap items-center justify-between gap-4">
				<h2 class="sr-only md:not-sr-only text-h2 text-(--list-color) lg:h-15">
					Tous les événements
				</h2>
				{@render viewToggle()}
			</div>
		</ListHeader>
	{:else if upcomingMonth}
		<ListHeader
			{color}
			count={upcomingForMonth.length}
			nouns={['événement', 'événements']}
			rule={false}
		>
			<div class="flex flex-wrap items-center justify-between gap-4">
				<div class="flex items-center gap-2 lg:gap-4 lg:h-15">
					<button
						type="button"
						class="text-(--list-color) p-1 disabled:opacity-30"
						aria-label="Mois précédent"
						disabled={upcomingIndex <= 0}
						onclick={() => goToMonth(-1)}
					>
						<IconChevron class="w-6.25 h-6.25 rotate-90" />
					</button>
					<!-- Every month shares the same cell so the box keeps the width of the longest label and the arrows never move. -->
					<div class="grid overflow-hidden">
						{#each upcomingMonths as month (month.value)}
							<span
								class="text-h2 invisible col-start-1 row-start-1 text-center"
								aria-hidden="true"
							>
								{month.label}
							</span>
						{/each}
						{#key upcomingMonth.value}
							<h2
								class="text-h2 text-(--list-color) col-start-1 row-start-1 text-center"
								in:fly={monthEnter}
								out:fly={monthLeave}
							>
								{upcomingMonth.label}
							</h2>
						{/key}
					</div>
					<button
						type="button"
						class="text-(--list-color) p-1 disabled:opacity-30"
						aria-label="Mois suivant"
						disabled={upcomingIndex >= upcomingMonths.length - 1}
						onclick={() => goToMonth(1)}
					>
						<IconChevron class="w-6.25 h-6.25 -rotate-90" />
					</button>
				</div>
				{@render viewToggle()}
			</div>
		</ListHeader>
	{/if}

	{#if visibleUpcoming.length > 0}
		<ul class="mt-9 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
			{#each visibleUpcoming as event (event.url)}
				<li class="aspect-3/4">
					<EventCard {event} {color} headingTag="h3" sizes={cardSizes} />
				</li>
			{/each}
		</ul>
	{/if}
</section>

{#if archive.current.matchTotal > 0}
	<section aria-labelledby="past-events-title" class="px-base pb-12 lg:pb-16">
		<CardTitle
			id="past-events-title"
			title="Les événements passés"
			class="text-center"
			pillClass="bg-blue text-white"
		/>

		<ListToolbar {color} class="mt-12 lg:mt-18">
			<SelectDropdown
				bind:value={selectedMonth}
				options={pastMonths}
				label="Mois"
				allLabel="Tous les mois"
				{color}
			/>

			{#snippet end()}
				<SearchInput
					bind:value={pastSearch}
					label="Rechercher un événement passé"
					placeholder="Rechercher un événement passé"
					color="blue"
				/>
			{/snippet}
		</ListToolbar>

		<p class="sr-only" aria-live="polite">
			{archive.total}
			{archive.total > 1 ? 'événements passés' : 'événement passé'}
		</p>

		{#if archive.total === 0}
			<div class="prose text-body-1 text-grey-dark text-center border-t border-black pt-12 mt-6">
				<!-- eslint-disable-next-line svelte/no-at-html-tags -- rich text comes from the trusted CMS writer field -->
				{@html page.noPastResultsText}
			</div>
		{:else}
			<div class="mt-6">
				{#each archive.items as event (event.url)}
					<EventListItem {event} {color} headingTag="h3" />
				{/each}
			</div>
		{/if}

		<InfiniteScroll
			hasMore={archive.hasMore}
			loadMore={archive.loadMore}
			key={archive.items.length}
			{color}
			label="Chargement des événements passés…"
			class="py-8"
		/>
	</section>
{/if}

<Blocks blocks={page.body} />
