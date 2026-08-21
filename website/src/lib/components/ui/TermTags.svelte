<script lang="ts">
	import { termColor } from '$lib/utils/shared';
	import type { TaxonomyTerm } from '$lib/interfaces/taxonomy';

	interface Props {
		terms: TaxonomyTerm[];
		label?: string | null;
		class?: string;
	}

	let { terms, label = null, class: className = '' }: Props = $props();
</script>

{#if terms.length > 0}
	<ul class={['flex flex-wrap items-center gap-3', className]} aria-label={label ?? undefined}>
		{#each terms as term (term.slug)}
			<li
				style:--term-color={termColor(term)}
				class="text-caption rounded-full border-2 border-(--term-color) text-(--term-color) px-3 py-0.5 leading-tight"
			>
				{term.title}
			</li>
		{/each}
	</ul>
{/if}
