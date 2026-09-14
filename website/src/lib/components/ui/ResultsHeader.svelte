<script lang="ts">
	import ListHeader from '$lib/components/ui/ListHeader.svelte';

	interface Props {
		/** The current search query; the heading is only shown when it is set. */
		query: string;
		count: number;
		/** Result noun, e.g. `['événement', 'événements']`. */
		nouns: [string, string];
		noResultsText?: string;
		color?: string;
		/** Band it is drawn as; `section` where it stands in for a browsable band. */
		variant?: 'section' | 'plain';
		/** Whether the band is topped by its rule, see `ListHeader`. */
		rule?: boolean;
		class?: string;
	}

	let {
		query,
		count,
		nouns,
		noResultsText = 'Aucun résultat ne correspond à votre recherche.',
		color = 'var(--color-teal)',
		variant = 'plain',
		rule = true,
		class: className = ''
	}: Props = $props();
</script>

<ListHeader {color} {variant} {count} {nouns} {rule} class={className}>
	<h2 class="text-h2 lg:h-15 text-(--list-color)">
		Résultats pour : <span class="opacity-50">{query}</span>
	</h2>

	{#if count === 0}
		<p class="text-body-1 mt-2.5 text-(--list-color)">{noResultsText}</p>
	{/if}
</ListHeader>
