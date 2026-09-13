<script lang="ts">
	import type { Snippet } from 'svelte';
	import { slide } from 'svelte/transition';
	import IconChevron from '$lib/components/svg/IconChevron.svelte';
	import ListHeader from '$lib/components/ui/ListHeader.svelte';

	interface Props {
		/** Id of the panel, so the heading button can point at it while it is open. */
		id: string;
		title: string;
		/** Any CSS colour: drives the rule, the heading and the chevron. */
		color?: string;
		open?: boolean;
		class?: string;
		/** Optional row under the heading, inside the band (a count, tags...). */
		meta?: Snippet;
		/** The panel content, revealed when the section is open. */
		children: Snippet;
	}

	let {
		id,
		title,
		color = 'var(--color-blue)',
		open = $bindable(false),
		class: className = '',
		meta,
		children
	}: Props = $props();
</script>

<!-- The section is browsed with the same band the other lists use, the heading
     itself opening and closing it. -->
<ListHeader {color} class={className}>
	<h2 class="text-h2 text-(--list-color) h-12.5">
		<button
			type="button"
			class="w-full flex items-center justify-between gap-4 text-left"
			aria-expanded={open}
			aria-controls={open ? id : undefined}
			onclick={() => (open = !open)}
		>
			{title}
			<IconChevron class="shrink-0 transition-transform {open ? 'rotate-180' : ''}" />
		</button>
	</h2>

	{#if meta}
		{@render meta()}
	{/if}
</ListHeader>

{#if open}
	<div {id} role="region" aria-label={title} transition:slide={{ duration: 300 }}>
		{@render children()}
	</div>
{/if}
