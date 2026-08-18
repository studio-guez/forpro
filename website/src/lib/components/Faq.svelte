<script lang="ts">
	import { browser } from '$app/environment';
	import { page as appPage } from '$app/state';
	import { replaceState } from '$app/navigation';
	import IconSearch from '$lib/components/svg/IconSearch.svelte';
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import FaqQuestion from '$lib/components/ui/FaqQuestion.svelte';
	import type { FaqItem, FaqPage, TaxonomyTerm } from '$lib/interfaces/faq';

	let { page }: { page: FaqPage } = $props();

	const termColor = (term: TaxonomyTerm): string =>
		term.color ? `var(--color-${term.color})` : 'var(--color-teal)';

	// Filters are initialised from the URL so filtered views can be shared/reloaded.
	const initialParams = appPage.url.searchParams;
	let search = $state(initialParams.get('q') ?? '');
	let selectedCategories = $state<string[]>(
		(initialParams.get('faqCategories') ?? '').split(',').filter(Boolean)
	);

	// Only offer terms that are actually used by at least one question.
	const usedTerms = $derived.by(() => {
		const terms: TaxonomyTerm[] = [];
		for (const section of page.sections) {
			for (const faq of section.faqs) {
				for (const term of faq.faqCategories) {
					if (!terms.some((t) => t.slug === term.slug)) terms.push(term);
				}
			}
		}
		return terms;
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

	// The first section starts open; filtering expands every matching section.
	let openSections = $state<number[]>([0]);
	const isSectionOpen = (index: number): boolean => isFiltering || openSections.includes(index);

	const toggleSection = (index: number): void => {
		openSections = openSections.includes(index)
			? openSections.filter((i) => i !== index)
			: [...openSections, index];
	};

	const toggleCategory = (slug: string): void => {
		selectedCategories = selectedCategories.includes(slug)
			? selectedCategories.filter((s) => s !== slug)
			: [...selectedCategories, slug];
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

<section class="px-base" aria-labelledby="faq-title">
	<h1 id="faq-title" class="text-h1 text-teal text-center">{page.title}</h1>

	<form
		role="search"
		class="mt-12 md:mt-18 flex justify-center"
		onsubmit={(event) => event.preventDefault()}
	>
		<label class="relative block w-full max-w-70">
			<span class="sr-only">Rechercher une question</span>
			<input
				type="search"
				bind:value={search}
				placeholder="Rechercher une question..."
				class="w-full rounded-full border-2 border-teal bg-transparent text-teal placeholder-teal font-bold text-lg px-5 py-3 pr-13 focus:border-teal focus:ring-teal"
			/>
			<IconSearch
				width={28}
				height={29}
				class="text-teal absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"
			/>
		</label>
	</form>

	{#if usedTerms.length > 0}
		<fieldset class="mt-12 md:mt-18">
			<legend class="text-body-2 text-center mx-auto">Questions concernant :</legend>
			<div
				class="mt-6 flex flex-wrap justify-center items-center gap-x-3 gap-y-4 max-w-4xl mx-auto"
			>
				{#each usedTerms as term (term.slug)}
					{@const selected = activeCategories.includes(term.slug)}
					<button
						type="button"
						style:--term-color={termColor(term)}
						class="text-label rounded-full border-2 border-(--term-color) px-4 py-1.5 leading-tight transition-colors {selected
							? 'bg-(--term-color) text-white'
							: 'bg-transparent text-(--term-color)'}"
						aria-pressed={selected}
						onclick={() => toggleCategory(term.slug)}
					>
						{term.title}
					</button>
				{/each}
				{#if activeCategories.length > 0}
					<button
						type="button"
						class="text-teal p-1"
						aria-label="Réinitialiser les filtres"
						onclick={() => (selectedCategories = [])}
					>
						<IconClose width={24} height={25} />
					</button>
				{/if}
			</div>
		</fieldset>
	{/if}
</section>

<section class="px-base" aria-label="Questions et réponses">
	<div aria-live="polite">
		{#if filteredSections.length === 0}
			<p class="text-body-1 text-grey-dark text-center border-t border-black pt-12">
				Aucune question ne correspond à votre recherche.
			</p>
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
						{section.faqs.length}
						{section.faqs.length > 1 ? 'questions' : 'question'}
						{#each section.commonTerms as term (term.slug)}
							<span
								style:--term-color={termColor(term)}
								class="text-caption rounded-full border-2 border-(--term-color) text-(--term-color) px-3 py-0.5 leading-tight"
							>
								{term.title}
							</span>
						{/each}
					</p>
					<div
						id="faq-section-{section.index}"
						role="region"
						aria-label={section.title}
						hidden={!open}
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
				</div>
			{/each}
		{/if}
	</div>
</section>
