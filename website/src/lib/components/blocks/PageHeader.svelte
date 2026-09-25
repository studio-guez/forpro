<script lang="ts">
	import PageHeaderCompact from '$lib/components/blocks/PageHeaderCompact.svelte';
	import PageHero from '$lib/components/blocks/PageHero.svelte';
	import PageIntro from '$lib/components/blocks/PageIntro.svelte';
	import type { PageHeaderData } from '$lib/interfaces/page';

	let { page }: { page: PageHeaderData } = $props();
</script>

{#if page.headerType === 'compact'}
	<PageHeaderCompact title={page.title} cover={page.cover} intro={page.intro} />
{:else}
	<PageHero title={page.title} overtitle={page.overtitle} theme={page.theme} cover={page.cover} />

	<PageIntro
		title={page.introTitle ?? ''}
		text={page.intro}
		layout={page.introLayout}
		cta={page.introCta}
		parentPage={page.parentPage}
		theme={page.theme}
		titleImage={page.introTitleImage}
	/>
{/if}
