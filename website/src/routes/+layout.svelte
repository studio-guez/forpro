<script lang="ts">
	import '../app.css';
	import SiteHeader from '$lib/components/SiteHeader.svelte';
	import type { LayoutData } from './$types';

	interface Props {
		data: LayoutData;
		children?: import('svelte').Snippet;
	}

	let { data, children }: Props = $props();
</script>

{#if data.header}
	<SiteHeader header={data.header} />
{/if}

<main class="max-w-360 mx-auto p-9 pt-27">
	{@render children?.()}
</main>
