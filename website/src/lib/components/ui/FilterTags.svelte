<script lang="ts">
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import type { TaxonomyFilterTerm, TaxonomyTerm } from '$lib/interfaces/taxonomy';
	import { termColor } from '$lib/utils/shared';
	import { TAG_BASE, tagColorClasses, tagSizeClasses } from '$lib/utils/tagStyles';

	interface Props {
		terms: TaxonomyFilterTerm[];
		selected: string[];
		legend: string;
		subLegend?: string;
		resetLabel?: string;
		color?: string;
		class?: string;
	}

	let {
		terms,
		selected = $bindable([]),
		legend,
		subLegend = 'Préciser :',
		resetLabel = 'Réinitialiser les filtres',
		color = 'var(--color-blue)',
		class: className = ''
	}: Props = $props();

	// Sub-terms of the selected parent terms only, kept in CMS order, each paired
	// with its parent so its selected state can be derived from it.
	const subTerms = $derived(
		terms
			.filter((term) => selected.includes(term.slug))
			.flatMap((parent) => parent.children.map((child) => ({ parent, child })))
	);

	// A selected parent stands for all of its sub-terms, so they all read as
	// selected until one of them narrows the selection down.
	const isChildSelected = (parent: TaxonomyFilterTerm, child: TaxonomyTerm): boolean =>
		selected.includes(child.slug) || !parent.children.some((c) => selected.includes(c.slug));

	// Sub-terms read as selected while their parent is, so clicking one takes it
	// out: the parent narrows down to the sub-terms left. Taking the last one out
	// deselects the parent as well, and leaving them all in is stored as the
	// parent alone, its default state.
	const toggleChild = (parent: TaxonomyFilterTerm, child: TaxonomyTerm): void => {
		const kept = parent.children
			.filter((term) => isChildSelected(parent, term) !== (term.slug === child.slug))
			.map((term) => term.slug);

		const dropped = new Set(parent.children.map((term) => term.slug));
		const base = selected.filter((slug) => !dropped.has(slug));

		if (kept.length === 0) {
			selected = base.filter((slug) => slug !== parent.slug);
			return;
		}

		selected = kept.length === parent.children.length ? base : [...base, ...kept];
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
		class={[TAG_BASE, tagSizeClasses.lg, tagColorClasses('lg', isSelected)]}
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
				style:--reset-color={color}
				class="text-(--reset-color) p-1 -mr-11.25 {selected.length > 0 ? '' : 'invisible'}"
				aria-label={resetLabel}
				inert={selected.length === 0}
				onclick={() => (selected = [])}
			>
				<IconClose class="w-6.25 h-6.25" />
			</button>
		</div>

		{#if subTerms.length > 0}
			<div
				role="group"
				aria-label="{subLegend} {legend}"
				class="mt-4 flex flex-wrap justify-center items-center gap-x-3 gap-y-4 max-w-4xl mx-auto"
			>
				{#each subTerms as { parent, child } (child.slug)}
					{@render tag(child, isChildSelected(parent, child), () => toggleChild(parent, child))}
				{/each}
			</div>
		{/if}
	</fieldset>
{/if}
