<script lang="ts">
	import { page as appPage } from '$app/state';
	import { fly } from 'svelte/transition';
	import { prefersReducedMotion } from 'svelte/motion';
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
		stripTags,
		syncQueryString
	} from '$lib/utils/filters';
	import { createPaginatedList } from '$lib/utils/paginatedList.svelte';
	import { CTA_BASE, ctaColorClasses } from '$lib/utils/ctaStyles';
	import { monthKey, monthKeyLabel, monthKeyOf, monthKeysBetween } from '$lib/utils/date';
	import type { AgendaEventCard } from '$lib/interfaces/page';
	import type { EventsPage, PastEventsList } from '$lib/interfaces/event';

	let { page }: { page: EventsPage } = $props();

	// Card grid: full-width section, 1 / sm:2 / lg:3 columns with a 1.5rem gutter.
	const cardSizes = toSizes(cell(PAGE, { 0: 1, 640: 2, 1024: 3 }, 1.5));

	const color = 'var(--color-blue)';
	const noResultsText = 'Aucun événement à venir ne correspond à votre recherche.';

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedPublics = $state<string[]>(parseListParam(initialParams.get('publics')));
	// The past archive has a search of its own: `q` above only covers upcoming events.
	let pastSearch = $state(initialParams.get('pastQ') ?? '');
	let selectedMonth = $state(initialParams.get('month') ?? '');

	// Every upcoming event is listed at once by default; the toggle swaps to
	// browsing them one month at a time. Mirrored into the URL so the month
	// view can be shared too.
	type UpcomingView = 'month' | 'all';
	let upcomingView = $state<UpcomingView>(initialParams.get('view') === 'month' ? 'month' : 'all');
	const showAllUpcoming = $derived(upcomingView === 'all');

	const toggleUpcomingView = (): void => {
		upcomingView = showAllUpcoming ? 'month' : 'all';
	};

	// The toggle is a smaller pill than the CTAs, but shares their outline and hover.
	const viewToggleClasses = `${CTA_BASE} ${ctaColorClasses(false)} text-sm gap-2 px-3.5 h-10 border-3 shrink-0`;

	// Events are only filtered by public; only offer terms actually used by at
	// least one event, in CMS order. The past archive is paginated, so which
	// terms it uses is answered by the CMS rather than counted here.
	const publicTerms = $derived(filterUsedTerms(page.publics, page.usedPublics));

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activePublics = $derived(keepKnownSlugs(selectedPublics, publicTerms));

	// Selecting a parent term also matches events tagged with one of its sub-terms.
	// Only the upcoming list needs this: the past archive is filtered by the CMS,
	// which resolves the raw selection itself.
	const publicFilter = $derived(expandSelection(activePublics, publicTerms));

	const matchesFilters = (event: AgendaEventCard): boolean =>
		matchesTerms(publicFilter, event.publics) &&
		matchesSearch(search, [
			event.title,
			stripTags(event.shortDesc),
			...[...event.programs, ...event.publics].map((term) => term.title)
		]);

	const upcoming = $derived(page.upcomingEvents.filter(matchesFilters));

	// Months already over are never offered, so an event that started in February
	// and runs until October is only listed from the current month on.
	const currentMonth = monthKeyOf(new Date());

	/** Every month an upcoming event runs in, from the current one at the earliest. */
	const eventMonths = (event: AgendaEventCard): string[] => {
		const start = monthKey(event.dateStart);
		if (!start) return [];

		const end = monthKey(event.dateEnd) ?? start;
		return monthKeysBetween(start < currentMonth ? currentMonth : start, end < start ? start : end);
	};

	// Upcoming events are paginated one month at a time, soonest month first; an
	// event spanning several months shows up under each of them. The selection
	// falls back to the first month, so a filter change that drops the current
	// month lands on a non-empty page.
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

	// Direction of the last month change. Both labels travel a full box width in
	// lockstep, so the leaving month looks pushed out by the arriving one. Motion
	// is dropped entirely when the visitor asked for it.
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

	// The past archive is paginated by the CMS, so it is filtered there too — the
	// page payload embeds the first page for the filters in the URL, so a shared
	// or reloaded filtered link renders the right archive server-side. Filters
	// travel raw, exactly as they appear in the URL. The agenda's search is not
	// one of them: it only answers across upcoming events. The archive has its
	// own, `pastQ` in the URL, sent to the archive route as its `q`.
	const archive = createPaginatedList<AgendaEventCard, PastEventsList>({
		kind: 'past-events',
		path: () => page.path,
		seed: () => page.pastEvents,
		filters: () => ({
			publics: activePublics.join(','),
			q: pastSearch.trim(),
			month: activeMonth
		})
	});

	// Past events are browsed month by month, most recent month first. The CMS
	// lists the months of the current match set whatever month is selected, so
	// the dropdown keeps offering the ones the selection excludes.
	//
	// Annotated because the months close a type cycle: they come out of the
	// archive, which is filtered by `activeMonth`, which is picked out of them.
	const pastMonths: { value: string; label: string }[] = $derived(
		archive.current.months.map((value) => ({ value, label: monthKeyLabel(value) }))
	);
	const activeMonth = $derived(
		pastMonths.some((month) => month.value === selectedMonth) ? selectedMonth : ''
	);

	const hasSearch = $derived(search.trim() !== '');

	// A search is answered across every upcoming event: the month browser steps
	// aside for the results header, and every matching event is listed at once —
	// as it is when the visitor asked for the whole list.
	const visibleUpcoming = $derived(hasSearch || showAllUpcoming ? upcoming : upcomingForMonth);
	const announcedCount = $derived(visibleUpcoming.length);

	// Mirror search + filters into the query string without triggering navigation.
	$effect(() => {
		syncQueryString({
			q: search,
			publics: activePublics,
			pastQ: pastSearch,
			month: activeMonth,
			view: showAllUpcoming ? '' : 'month'
		});
	});
</script>

{#snippet viewToggle()}
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
{/snippet}

<BasicHeader title={page.title} {color} id="events-title"></BasicHeader>

<section aria-label="Événements à venir" class="px-base pb-12 lg:pb-16">
	<ListToolbar>
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
			{noResultsText}
			{color}
			variant="section"
			rule={false}
		/>
	{:else if upcoming.length === 0}
		<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12">
			Aucun événement à venir pour le moment.
		</p>
	{:else if showAllUpcoming}
		<!-- The first band has no rule: the toolbar already parts it from the header. -->
		<ListHeader {color} count={upcoming.length} nouns={['événement', 'événements']} rule={false}>
			<div class="flex flex-wrap items-center justify-between gap-4">
				<h2 class="text-h2 text-(--list-color) h-12.5">Tous les événements</h2>
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
				<div class="flex items-center gap-2 lg:gap-4 h-12.5">
					<button
						type="button"
						class="text-(--list-color) p-1 disabled:opacity-30"
						aria-label="Mois précédent"
						disabled={upcomingIndex <= 0}
						onclick={() => goToMonth(-1)}
					>
						<IconChevron class="w-6.25 h-6.25 rotate-90" />
					</button>
					<!-- Every month is laid out in the same cell, so the box keeps the width of the
					     longest label, the arrows never move and each label stays centred whatever
					     its length. The two labels in flight travel a full box width in lockstep,
					     which is what makes the leaving one look pushed out by the arriving one. -->
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

		<!-- The archive is browsed with its own toolbar: the month at the start,
		     its own search at the end. -->
		<ListToolbar class="mt-12 lg:mt-18">
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
			<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12 mt-6">
				Aucun événement passé ne correspond à votre recherche.
			</p>
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
