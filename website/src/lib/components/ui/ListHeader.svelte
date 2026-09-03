<script lang="ts">
	import type { Snippet } from 'svelte';

	interface Props {
		/** Any CSS colour: exposed as `--list-color`, which drives the rule and the count. */
		color?: string;
		/**
		 * `section` is the band a list section is browsed with — a thick rule in the
		 * section colour, tight above its heading. `plain` is the lighter rule used
		 * where the band only announces search results.
		 */
		variant?: 'section' | 'plain';
		/** Items in the list below; the count line is dropped when there are none. */
		count?: number;
		/** Count noun, e.g. `['événement', 'événements']`. */
		nouns?: [string, string];
		class?: string;
		/** The heading row: title on the left, controls on the right. */
		children: Snippet;
	}

	let {
		color = 'var(--color-blue)',
		variant = 'section',
		count = 0,
		nouns = ['résultat', 'résultats'],
		class: className = '',
		children
	}: Props = $props();

	// The two rules differ in weight, so the room they need above the heading
	// differs with them.
	const chrome = {
		section: { rule: 'border-t-3 border-(--list-color) pt-3', count: 'mt-4' },
		plain: { rule: 'border-t border-black pt-6 lg:pt-8', count: 'mt-1' }
	};
</script>

<div style:--list-color={color} class={[chrome[variant].rule, className]}>
	{@render children()}

	{#if count > 0}
		<p class="text-label text-(--list-color) {chrome[variant].count}">
			{count}
			{count > 1 ? nouns[1] : nouns[0]}
		</p>
	{/if}
</div>
