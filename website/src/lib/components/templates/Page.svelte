<script lang="ts">
	import PageHeader from '$lib/components/blocks/PageHeader.svelte';
	import Blocks from '$lib/components/blocks/Blocks.svelte';
	import type { Page } from '$lib/interfaces/page';

	let { page }: { page: Page } = $props();
</script>

<PageHeader {page} />

<Blocks blocks={page.body} theme={page.theme} />
