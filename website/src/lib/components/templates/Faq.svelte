<script lang="ts">
	import { slide } from 'svelte/transition';
	import { page as appPage } from '$app/state';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import FaqQuestion from '$lib/components/ui/FaqQuestion.svelte';
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
		stripTags,
		syncQueryString
	} from '$lib/utils/filters';
	import type { FaqItem, FaqPage } from '$lib/interfaces/faq';

	let { page }: { page: FaqPage } = $props();

	const noResultsText = 'Aucune question ne correspond à votre recherche.';

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedSectors = $state<string[]>(parseListParam(initialParams.get('sectors')));
	let selectedPrograms = $state<string[]>(parseListParam(initialParams.get('programs')));
	let selectedPublics = $state<string[]>(parseListParam(initialParams.get('publics')));

	const allFaqs = $derived(page.sections.flatMap((section) => section.faqs));

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

	// The first section starts open; filtering expands every matching section.
	let openSections = $state<number[]>([0]);
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
	$effect(() => {
		syncQueryString({
			q: search,
			sectors: activeSectors,
			programs: activePrograms,
			publics: activePublics
		});
	});
</script>

{#snippet questionCount(count: number)}
	{count}
	{count > 1 ? 'questions' : 'question'}
{/snippet}

<section aria-labelledby="faq-title">
	<h1 id="faq-title" class="text-h1 text-teal text-center">{page.title}</h1>

	<SearchInput
		bind:value={search}
		label="Rechercher une question"
		placeholder="Rechercher une question..."
		class="mt-12 md:mt-18"
	/>

	<FilterTags
		terms={sectorTerms}
		bind:selected={selectedSectors}
		legend="Questions concernant :"
		class="mt-12 md:mt-18"
	/>

	<FilterTags
		terms={programTerms}
		bind:selected={selectedPrograms}
		legend="Programmes :"
		class="mt-9 md:mt-12"
	/>

	<FilterTags
		terms={publicTerms}
		bind:selected={selectedPublics}
		legend="Publics :"
		class="mt-9 md:mt-12"
	/>
</section>

<section aria-label="Questions et réponses">
	<div aria-live="polite">
		{#if hasSearch}
			<div class="py-6 md:py-8">
				<ResultsHeader
					query={search.trim()}
					count={searchResults.length}
					nouns={['question', 'questions']}
					onClear={clearSearch}
					{noResultsText}
				/>
				{#if searchResults.length > 0}
					<div class="mt-9 space-y-6">
						{#each searchResults as faq, faqIndex (faqIndex)}
							<FaqQuestion
								id="faq-search-{faqIndex}"
								question={faq.question}
								answer={faq.answer}
							/>
						{/each}
					</div>
				{/if}
			</div>
		{:else if filteredSections.length === 0}
			<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12">{noResultsText}</p>
		{:else}
			{#each filteredSections as section (section.index)}
				{@const open = isSectionOpen(section.index)}
				<div class="border-t border-black py-6 md:py-8">
					<h2 class="text-h2 text-teal">
						<button
							type="button"
							class="w-full flex items-start justify-between gap-4 text-left"
							aria-expanded={open}
							aria-controls="faq-section-{section.index}"
							onclick={() => toggleSection(section.index)}
						>
							{section.title}
							<IconChevron
								class="text-teal shrink-0 mt-[.5em] transition-transform {open ? 'rotate-180' : ''}"
							/>
						</button>
					</h2>
					<div class="mt-1 flex flex-wrap items-center gap-3">
						<p class="text-label">{@render questionCount(section.faqs.length)}</p>
						<TermTags terms={section.commonTerms} label="Secteurs" />
					</div>
					{#if open}
						<div
							id="faq-section-{section.index}"
							role="region"
							aria-label={section.title}
							transition:slide={{ duration: 300 }}
						>
							<div class="mt-9 space-y-6">
								{#each section.faqs as faq, faqIndex (faqIndex)}
									<FaqQuestion
										id="faq-{section.index}-{faqIndex}"
										question={faq.question}
										answer={faq.answer}
									/>
								{/each}
							</div>
						</div>
					{/if}
				</div>
			{/each}
		{/if}
	</div>
</section>

<Blocks blocks={page.body} />
