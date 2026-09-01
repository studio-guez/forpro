<script lang="ts">
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import type { TaxonomyFilterTerm, TaxonomyTerm } from '$lib/interfaces/taxonomy';
	import { termColor } from '$lib/utils/shared';

	interface Props {
		terms: TaxonomyFilterTerm[];
		selected: string[];
		legend: string;
		subLegend?: string;
		resetLabel?: string;
		class?: string;
	}

	let {
		terms,
		selected = $bindable([]),
		legend,
		subLegend = 'Préciser :',
		resetLabel = 'Réinitialiser les filtres',
		class: className = ''
	}: Props = $props();

	// Sub-terms of the selected parent terms only, kept in CMS order.
	const subTerms = $derived(
		terms.filter((term) => selected.includes(term.slug)).flatMap((term) => term.children)
	);

	const toggle = (slug: string): void => {
		selected = selected.includes(slug) ? selected.filter((s) => s !== slug) : [...selected, slug];
	};

	// Deselecting a parent hides its sub-terms, so their selection has to go with it.
	const toggleParent = (term: TaxonomyFilterTerm): void => {
		if (!selected.includes(term.slug)) {
			selected = [...selected, term.slug];
			return;
		}

		const dropped = new Set([term.slug, ...term.children.map((child) => child.slug)]);
		selected = selected.filter((slug) => !dropped.has(slug));
	};
</script>

{#snippet tag(term: TaxonomyTerm, isSelected: boolean, onclick: () => void)}
	<button
		type="button"
		style:--term-color={termColor(term)}
		class="text-label rounded-full border-2 border-(--term-color) px-4 py-1.5 leading-tight transition-colors {isSelected
			? 'bg-(--term-color) text-white'
			: 'bg-transparent text-(--term-color)'}"
		aria-pressed={isSelected}
		{onclick}
	>
		{term.title}
	</button>
{/snippet}

{#if terms.length > 0}
	<fieldset class={className}>
		<legend class="text-body-2 text-center mx-auto">{legend}</legend>
		<div class="mt-6 flex flex-wrap justify-center items-center gap-x-3 gap-y-4 max-w-4xl mx-auto">
			{#each terms as term (term.slug)}
				{@render tag(term, selected.includes(term.slug), () => toggleParent(term))}
			{/each}
			<button
				type="button"
				class="text-teal p-1 {selected.length > 0 ? '' : 'invisible'}"
				aria-label={resetLabel}
				aria-hidden={selected.length === 0}
				tabindex={selected.length > 0 ? 0 : -1}
				onclick={() => (selected = [])}
			>
				<IconClose class="w-6 h-6.25" />
			</button>
		</div>

		{#if subTerms.length > 0}
			<div
				role="group"
				aria-label="{subLegend} {legend}"
				class="mt-4 flex flex-wrap justify-center items-center gap-x-3 gap-y-4 max-w-4xl mx-auto"
			>
				{#each subTerms as term (term.slug)}
					{@render tag(term, selected.includes(term.slug), () => toggle(term.slug))}
				{/each}
			</div>
		{/if}
	</fieldset>
{/if}
