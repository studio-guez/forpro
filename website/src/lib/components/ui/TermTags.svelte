<script lang="ts">
	import { termColor } from '$lib/utils/shared';
	import { TAG_BASE, tagColorClasses, tagSizeClasses, type TagSize } from '$lib/utils/tagStyles';
	import type { TaxonomyTerm } from '$lib/interfaces/taxonomy';

	interface Props {
		terms: TaxonomyTerm[];
		label?: string | null;
		size?: TagSize;
		class?: string;
	}

	let { terms, label = null, size = 'lg', class: className = '' }: Props = $props();
</script>

{#if terms.length > 0}
	<ul class={['flex flex-wrap items-center gap-x-3 gap-y-1.5', className]} aria-label={label ?? undefined}>
		{#each terms as term (term.slug)}
			<li
				style:--term-color={termColor(term)}
				class={[TAG_BASE, tagSizeClasses[size], tagColorClasses(size, false)]}
			>
				{term.title}
			</li>
		{/each}
	</ul>
{/if}
