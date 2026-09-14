<script lang="ts">
	import { page as appPage } from '$app/state';
	import BasicHeader from '$lib/components/blocks/BasicHeader.svelte';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import ExpandableSection from '$lib/components/ui/ExpandableSection.svelte';
	import FaqQuestion from '$lib/components/ui/FaqQuestion.svelte';
	import ResultsHeader from '$lib/components/ui/ResultsHeader.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import FilterDropdown from '$lib/components/ui/FilterDropdown.svelte';
	import ListToolbar from '$lib/components/ui/ListToolbar.svelte';
	import {
		expandSelection,
		filterUsedTerms,
		keepKnownSlugs,
		matchesSearch,
		matchesTerms,
		parseListParam,
		slugify,
		stripTags,
		syncQueryString
	} from '$lib/utils/filters';
	import type { FaqItem, FaqPage } from '$lib/interfaces/faq';

	let { page }: { page: FaqPage } = $props();

	const color = 'var(--color-teal)';
	const noResultsText = 'Aucune question ne correspond à votre recherche.';

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedSectors = $state<string[]>(parseListParam(initialParams.get('sectors')));
	let selectedPrograms = $state<string[]>(parseListParam(initialParams.get('programs')));
	let selectedPublics = $state<string[]>(parseListParam(initialParams.get('publics')));

	const allFaqs = $derived(page.sections.flatMap((section) => section.faqs));

	// A shareable id per question, so `?question=<id>` opens one directly. Two
	// questions worded the same are told apart by a suffix, in page order.
	const questionIds = $derived.by(() => {
		const ids = new Map<FaqItem, string>();
		const seen = new Map<string, number>();

		for (const faq of allFaqs) {
			const base = slugify(faq.question) || 'question';
			const count = (seen.get(base) ?? 0) + 1;
			seen.set(base, count);
			ids.set(faq, count > 1 ? `${base}-${count}` : base);
		}

		return ids;
	});
	const questionId = (faq: FaqItem): string => questionIds.get(faq) ?? '';

	// Only offer terms that are actually used by at least one question,
	// kept in the CMS-defined taxonomy order.
	const sectorTerms = $derived(
		filterUsedTerms(
			page.sectors,
			allFaqs.flatMap((faq) => faq.sectors.map((term) => term.slug))
		)
	);
	const programTerms = $derived(
		filterUsedTerms(
			page.programs,
			allFaqs.flatMap((faq) => faq.programs.map((term) => term.slug))
		)
	);
	const publicTerms = $derived(
		filterUsedTerms(
			page.publics,
			allFaqs.flatMap((faq) => faq.publics.map((term) => term.slug))
		)
	);

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activeSectors = $derived(keepKnownSlugs(selectedSectors, sectorTerms));
	const activePrograms = $derived(keepKnownSlugs(selectedPrograms, programTerms));
	const activePublics = $derived(keepKnownSlugs(selectedPublics, publicTerms));

	// Selecting a parent term also matches questions tagged with one of its sub-terms.
	const sectorFilter = $derived(expandSelection(activeSectors, sectorTerms));
	const programFilter = $derived(expandSelection(activePrograms, programTerms));
	const publicFilter = $derived(expandSelection(activePublics, publicTerms));

	const matchesFilters = (faq: FaqItem): boolean =>
		matchesTerms(sectorFilter, faq.sectors) &&
		matchesTerms(programFilter, faq.programs) &&
		matchesTerms(publicFilter, faq.publics) &&
		matchesSearch(search, [faq.question, stripTags(faq.answer)]);

	const isFiltering = $derived(
		search.trim() !== '' ||
			activeSectors.length > 0 ||
			activePrograms.length > 0 ||
			activePublics.length > 0
	);

	const filteredSections = $derived(
		page.sections
			.map((section, index) => ({
				index,
				title: section.title,
				faqs: section.faqs.filter(matchesFilters)
			}))
			.filter((section) => section.faqs.length > 0)
	);

	// A search term collapses every section into a single flat result list.
	const hasSearch = $derived(search.trim() !== '');
	const searchResults = $derived(
		hasSearch ? page.sections.flatMap((section) => section.faqs.filter(matchesFilters)) : []
	);

	const announcedCount = $derived(
		hasSearch
			? searchResults.length
			: filteredSections.reduce((total, section) => total + section.faqs.length, 0)
	);

	// A shared link names one question: it is rendered open, inside its own
	// section, server-side — so the answer is there before any script runs.
	const requestedQuestion = initialParams.get('question') ?? '';
	const sectionOfQuestion = (id: string): number =>
		page.sections.findIndex((section) => section.faqs.some((faq) => questionId(faq) === id));

	let openQuestions = $state<Record<string, boolean>>(
		requestedQuestion ? { [requestedQuestion]: true } : {}
	);

	// The deep-linked section starts open, the first one otherwise; filtering
	// expands every matching section.
	let openSections = $state<number[]>([Math.max(sectionOfQuestion(requestedQuestion), 0)]);
	const isSectionOpen = (index: number): boolean => isFiltering || openSections.includes(index);

	const setSectionOpen = (index: number, open: boolean): void => {
		openSections = open ? [...openSections, index] : openSections.filter((i) => i !== index);
	};

	// Mirror search + filters into the query string without triggering navigation.
	// The deep-linked question travels with them for as long as it stays open, so
	// the page can be reloaded on it.
	$effect(() => {
		syncQueryString({
			q: search,
			sectors: activeSectors,
			programs: activePrograms,
			publics: activePublics,
			question: openQuestions[requestedQuestion] ? requestedQuestion : ''
		});
	});

	// Rendering it open is not enough to find it: a question further down the page
	// is brought into view once the page is interactive.
	$effect(() => {
		if (!requestedQuestion) return;

		document.getElementById(`faq-${requestedQuestion}`)?.scrollIntoView({ block: 'center' });
	});
</script>

{#snippet questionCount(count: number)}
	{count}
	{count > 1 ? 'questions' : 'question'}
{/snippet}

{#snippet questionItem(faq: FaqItem)}
	{@const id = questionId(faq)}
	<FaqQuestion
		id="faq-{id}"
		question={faq.question}
		answer={faq.answer}
		{color}
		shareUrl="?question={id}"
		bind:open={() => openQuestions[id] ?? false, (value) => (openQuestions[id] = value)}
	/>
{/snippet}

<BasicHeader title={page.title} {color} id="faq-title"></BasicHeader>

<section aria-label="Questions et réponses" class="px-base pb-12 lg:pb-16">
	<ListToolbar {color}>
		<FilterDropdown terms={sectorTerms} bind:selected={selectedSectors} label="Secteurs" {color} />
		<FilterDropdown
			terms={programTerms}
			bind:selected={selectedPrograms}
			label="Programmes"
			{color}
		/>
		<FilterDropdown terms={publicTerms} bind:selected={selectedPublics} label="Publics" {color} />

		{#snippet end()}
			<SearchInput
				bind:value={search}
				label="Rechercher une question"
				placeholder="Rechercher une question"
			/>
		{/snippet}
	</ListToolbar>

	<p class="sr-only" aria-live="polite">
		{@render questionCount(announcedCount)}
	</p>

	{#if hasSearch}
		<ResultsHeader
			query={search.trim()}
			count={searchResults.length}
			nouns={['question', 'questions']}
			{noResultsText}
			{color}
			variant="section"
			rule={false}
		/>

		{#if searchResults.length > 0}
			<div class="mt-9 space-y-6">
				{#each searchResults as faq (questionId(faq))}
					{@render questionItem(faq)}
				{/each}
			</div>
		{/if}
	{:else if filteredSections.length === 0}
		<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12">
			{noResultsText}
		</p>
	{:else}
		{#each filteredSections as section, position (section.index)}
			<!-- The first band follows the toolbar directly, without a rule: the toolbar
			     already parts it from the header. The others keep their rule and distance. -->
			<ExpandableSection
				id="faq-section-{section.index}"
				title={section.title}
				{color}
				rule={position !== 0}
				class={position === 0 ? '' : 'mt-12 lg:mt-18'}
				bind:open={
					() => isSectionOpen(section.index), (value) => setSectionOpen(section.index, value)
				}
			>
				{#snippet meta()}
					<p class="mt-2.5 text-label text-(--list-color)">
						{@render questionCount(section.faqs.length)}
					</p>
				{/snippet}

				<div class="mt-9 space-y-6">
					{#each section.faqs as faq (questionId(faq))}
						{@render questionItem(faq)}
					{/each}
				</div>
			</ExpandableSection>
		{/each}
	{/if}
</section>

<Blocks blocks={page.body} />
