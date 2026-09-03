<script lang="ts">
	import type { Snippet } from 'svelte';

	interface Props {
		title: string;
		/** Any CSS colour: drives the heading. */
		color?: string;
		/** Id of the `h1`, referenced by the section's `aria-labelledby`. */
		id?: string;
		class?: string;
		/** Controls belonging to the header section (search, filters). */
		children?: Snippet;
	}

	let {
		title,
		color = 'var(--color-blue)',
		id = 'page-title',
		class: className = '',
		children
	}: Props = $props();
</script>

<section aria-labelledby={id} class={['mb-12 lg:mb-24 lg:pt-13', className]}>
	<h1 {id} style:color class="text-h1 text-center">{title}</h1>

	{@render children?.()}
</section>
