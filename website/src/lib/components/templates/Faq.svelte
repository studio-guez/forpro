<script lang="ts">
	import { slide } from 'svelte/transition';
	import { page as appPage } from '$app/state';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import BasicHeader from '$lib/components/blocks/BasicHeader.svelte';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import FaqQuestion from '$lib/components/ui/FaqQuestion.svelte';
	import ListHeader from '$lib/components/ui/ListHeader.svelte';
	import ResultsHeader from '$lib/components/ui/ResultsHeader.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
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
				faqs: section.faqs.filter(matchesFilters),
				// Terms shared by every question of the section (shown next to the counter).
				commonTerms: section.faqs.length
					? section.faqs[0].sectors.filter((term) =>
							section.faqs.every((faq) => faq.sectors.some((t) => t.slug === term.slug))
						)
					: []
			}))
			.filter((section) => section.faqs.length > 0)
	);

	// A search term collapses every section into a single flat result list.
	const hasSearch = $derived(search.trim() !== '');
	const searchResults = $derived(
		hasSearch ? page.sections.flatMap((section) => section.faqs.filter(matchesFilters)) : []
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

	const toggleSection = (index: number): void => {
		openSections = openSections.includes(index)
			? openSections.filter((i) => i !== index)
			: [...openSections, index];
	};

	const clearSearch = (): void => {
		search = '';
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

<BasicHeader title={page.title} {color} id="faq-title">
	<SearchInput
		bind:value={search}
		label="Rechercher une question"
		placeholder="Rechercher une question..."
		class="mt-12 lg:mt-18"
	/>

	<FilterTags
		terms={sectorTerms}
		bind:selected={selectedSectors}
		{color}
		legend="Questions concernant :"
		class="mt-12 lg:mt-18"
	/>

	<FilterTags
		terms={programTerms}
		bind:selected={selectedPrograms}
		{color}
		legend="Programmes :"
		class="mt-9 lg:mt-12"
	/>

	<FilterTags
		terms={publicTerms}
		bind:selected={selectedPublics}
		{color}
		legend="Publics :"
		class="mt-9 lg:mt-12"
	/>
</BasicHeader>

<section aria-label="Questions et réponses" class="px-base pb-12 lg:pb-16">
	<div aria-live="polite">
		{#if hasSearch}
			<ResultsHeader
				query={search.trim()}
				count={searchResults.length}
				nouns={['question', 'questions']}
				onClear={clearSearch}
				{noResultsText}
				{color}
				variant="section"
				class="mt-12 lg:mt-18"
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
			{#each filteredSections as section (section.index)}
				{@const open = isSectionOpen(section.index)}
				<!-- Every section is browsed with the same band the other lists use, the
				     heading itself opening and closing it. -->
				<ListHeader {color} class="mt-12 lg:mt-18">
					<h2 class="text-h2 text-(--list-color) h-12.5">
						<button
							type="button"
							class="w-full flex items-center justify-between gap-4 text-left"
							aria-expanded={open}
							aria-controls="faq-section-{section.index}"
							onclick={() => toggleSection(section.index)}
						>
							{section.title}
							<IconChevron class="shrink-0 transition-transform {open ? 'rotate-180' : ''}" />
						</button>
					</h2>

					<div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-3">
						<p class="text-label text-(--list-color)">
							{@render questionCount(section.faqs.length)}
						</p>
						<TermTags terms={section.commonTerms} label="Secteurs" size="md" />
					</div>
				</ListHeader>

				{#if open}
					<div
						id="faq-section-{section.index}"
						role="region"
						aria-label={section.title}
						transition:slide={{ duration: 300 }}
					>
						<div class="mt-9 space-y-6">
							{#each section.faqs as faq (questionId(faq))}
								{@render questionItem(faq)}
							{/each}
						</div>
					</div>
				{/if}
			{/each}
		{/if}
	</div>
</section>

<Blocks blocks={page.body} />
