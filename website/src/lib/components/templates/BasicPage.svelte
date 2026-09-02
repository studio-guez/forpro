<script lang="ts">
	import BasicHeader from '$lib/components/blocks/BasicHeader.svelte';
	import EventProjectBlocks from '$lib/components/blocks/EventProjectBlocks.svelte';
	import type { BasicPage } from '$lib/interfaces/basicPage';

	let { page }: { page: BasicPage } = $props();
</script>

<article>
	<BasicHeader title={page.title} />

	<EventProjectBlocks blocks={page.blocks} />
</article>
