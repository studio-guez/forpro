<script lang="ts">
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import ListHeader from '$lib/components/ui/ListHeader.svelte';
	import {
		CTA_BASE,
		ctaColorClasses,
		ctaIconSizeClasses,
		ctaSizeClasses
	} from '$lib/utils/ctaStyles';

	interface Props {
		/** The current search query; the heading is only shown when it is set. */
		query: string;
		count: number;
		/** Result noun, e.g. `['événement', 'événements']`. */
		nouns: [string, string];
		onClear: () => void;
		noResultsText?: string;
		color?: string;
		/** Band it is drawn as; `section` where it stands in for a browsable band. */
		variant?: 'section' | 'plain';
		class?: string;
	}

	let {
		query,
		count,
		nouns,
		onClear,
		noResultsText = 'Aucun résultat ne correspond à votre recherche.',
		color = 'var(--color-teal)',
		variant = 'plain',
		class: className = ''
	}: Props = $props();

	const colorClasses = ctaColorClasses(false);
</script>

<ListHeader {color} {variant} {count} {nouns} class={className}>
	<div class="flex flex-wrap items-start justify-between gap-4 text-(--list-color)">
		<h2 class="text-h2">
			Résultats pour : <span class="opacity-50">{query}</span>
		</h2>

		<button
			type="button"
			style:--color-cta="var(--list-color)"
			class="{CTA_BASE} {colorClasses} {ctaSizeClasses.md} shrink-0"
			onclick={onClear}
		>
			<span class="text-trim">Effacer la recherche</span>
			<IconClose class={ctaIconSizeClasses.md} />
		</button>
	</div>

	{#if count === 0}
		<p class="text-body-1 mt-4 text-(--list-color)">{noResultsText}</p>
	{/if}
</ListHeader>
