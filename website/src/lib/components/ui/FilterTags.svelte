<script lang="ts">
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import type { TaxonomyTerm } from '$lib/interfaces/taxonomy';
	import { termColor } from '$lib/utils/shared';

	interface Props {
		terms: TaxonomyTerm[];
		selected: string[];
		legend: string;
		resetLabel?: string;
		class?: string;
	}

	let {
		terms,
		selected = $bindable([]),
		legend,
		resetLabel = 'Réinitialiser les filtres',
		class: className = ''
	}: Props = $props();

	const toggle = (slug: string): void => {
		selected = selected.includes(slug)
			? selected.filter((s) => s !== slug)
			: [...selected, slug];
	};
</script>

{#if terms.length > 0}
	<fieldset class={className}>
		<legend class="text-body-2 text-center mx-auto">{legend}</legend>
		<div class="mt-6 flex flex-wrap justify-center items-center gap-x-3 gap-y-4 max-w-4xl mx-auto">
			{#each terms as term (term.slug)}
				{@const isSelected = selected.includes(term.slug)}
				<button
					type="button"
					style:--term-color={termColor(term)}
					class="text-label rounded-full border-2 border-(--term-color) px-4 py-1.5 leading-tight transition-colors {isSelected
						? 'bg-(--term-color) text-white'
						: 'bg-transparent text-(--term-color)'}"
					aria-pressed={isSelected}
					onclick={() => toggle(term.slug)}
				>
					{term.title}
				</button>
			{/each}
			<button
				type="button"
				class="text-teal p-1 {selected.length > 0 ? '' : 'invisible'}"
				aria-label={resetLabel}
				aria-hidden={selected.length === 0}
				tabindex={selected.length > 0 ? 0 : -1}
				onclick={() => (selected = [])}
			>
				<IconClose width={24} height={25} />
			</button>
		</div>
	</fieldset>
{/if}
