<script lang="ts">
	import { browser } from '$app/environment';
	import { slide } from 'svelte/transition';
	import { page as appPage } from '$app/state';
	import { replaceState } from '$app/navigation';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import FaqQuestion from '$lib/components/ui/FaqQuestion.svelte';
	import SearchInput from '$lib/components/ui/SearchInput.svelte';
	import FilterTags from '$lib/components/ui/FilterTags.svelte';
	import { termColor } from '$lib/utils/shared';
	import type { FaqItem, FaqPage } from '$lib/interfaces/faq';

	let { page }: { page: FaqPage } = $props();

	const noResultsText = 'Aucune question ne correspond à votre recherche.';

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedCategories = $state<string[]>(
		(initialParams.get('faqCategories') ?? '').split(',').filter(Boolean)
	);

	// Only offer terms that are actually used by at least one question,
	// kept in the CMS-defined taxonomy order.
	const usedTerms = $derived.by(() => {
		const used = new Set<string>();
		for (const section of page.sections) {
			for (const faq of section.faqs) {
				for (const term of faq.faqCategories) used.add(term.slug);
			}
		}
		return page.faqCategories.filter((term) => used.has(term.slug));
	});

	// Drop stale slugs coming from the URL so counters stay accurate.
	const activeCategories = $derived(
		selectedCategories.filter((slug) => usedTerms.some((term) => term.slug === slug))
	);

	const normalize = (value: string): string =>
		value
			.toLowerCase()
			.normalize('NFD')
			.replace(/\p{Diacritic}/gu, '');

	const stripTags = (html: string): string => html.replace(/<[^>]*>/g, ' ');

	const matchesFilters = (faq: FaqItem): boolean => {
		if (
			activeCategories.length > 0 &&
			!faq.faqCategories.some((term) => activeCategories.includes(term.slug))
		) {
			return false;
		}
		const query = normalize(search.trim());
		if (query === '') return true;
		return normalize(`${faq.question} ${stripTags(faq.answer)}`).includes(query);
	};

	const isFiltering = $derived(search.trim() !== '' || activeCategories.length > 0);

	const filteredSections = $derived(
		page.sections
			.map((section, index) => ({
				index,
				title: section.title,
				faqs: section.faqs.filter(matchesFilters),
				// Terms shared by every question of the section (shown next to the counter).
				commonTerms: section.faqs.length
					? section.faqs[0].faqCategories.filter((term) =>
							section.faqs.every((faq) => faq.faqCategories.some((t) => t.slug === term.slug))
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

	// Mirror search + filters into the query string without triggering navigation.
	$effect(() => {
		const parts: string[] = [];
		if (search.trim() !== '') parts.push(`q=${encodeURIComponent(search.trim())}`);
		if (activeCategories.length > 0)
			parts.push(`faqCategories=${encodeURIComponent(activeCategories.join(','))}`);
		const query = parts.length > 0 ? `?${parts.join('&')}` : '';
		if (!browser || window.location.search === query) return;
		replaceState(`${window.location.pathname}${query}`, {});
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
		terms={usedTerms}
		bind:selected={selectedCategories}
		legend="Questions concernant :"
		class="mt-12 md:mt-18"
	/>
</section>

<section aria-label="Questions et réponses">
	<div aria-live="polite">
		{#if hasSearch}
			<div class="border-t border-black py-6 md:py-8">
				<h2 class="text-h2 text-teal">Résultat pour : {search.trim()}</h2>
				{#if searchResults.length === 0}
					<p class="text-body-1 text-grey-dark mt-1">{noResultsText}</p>
				{:else}
					<p class="text-label mt-1">{@render questionCount(searchResults.length)}</p>
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
					<p class="text-label mt-1 flex flex-wrap items-center gap-3">
						{@render questionCount(section.faqs.length)}
						{#each section.commonTerms as term (term.slug)}
							<span
								style:--term-color={termColor(term)}
								class="text-caption rounded-full border-2 border-(--term-color) text-(--term-color) px-3 py-0.5 leading-tight"
							>
								{term.title}
							</span>
						{/each}
					</p>
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
