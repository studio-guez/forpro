<script lang="ts">
	import IconClose from '$lib/components/svg/IconClose.svelte';

	interface Props {
		/** The current search query; the heading is only shown when it is set. */
		query: string;
		count: number;
		/** Result noun, e.g. `['événement', 'événements']`. */
		nouns: [string, string];
		onClear: () => void;
		noResultsText?: string;
		color?: string;
		class?: string;
	}

	let {
		query,
		count,
		nouns,
		onClear,
		noResultsText = 'Aucun résultat ne correspond à votre recherche.',
		color = 'var(--color-teal)',
		class: className = ''
	}: Props = $props();
</script>

<div style:--results-color={color} class="border-t border-black pt-6 md:pt-8 {className}">
	<div class="flex flex-wrap items-start justify-between gap-4">
		<div>
			<h2 class="text-h2 text-(--results-color)">
				Résultats pour : <span class="text-grey-light">{query}</span>
			</h2>
			{#if count > 0}
				<p class="text-label text-(--results-color) mt-1">
					{count}
					{count > 1 ? nouns[1] : nouns[0]}
				</p>
			{/if}
		</div>

		<button
			type="button"
			class="text-label shrink-0 flex items-center gap-2 rounded-full border-2 border-(--results-color) text-(--results-color) px-4 py-1.5 leading-tight transition-colors hover:bg-(--results-color) hover:text-white"
			onclick={onClear}
		>
			Effacer la recherche
			<IconClose width={18} height={19} />
		</button>
	</div>

	{#if count === 0}
		<p class="text-body-1 text-grey-dark mt-4">{noResultsText}</p>
	{/if}
</div>
