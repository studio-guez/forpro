<script lang="ts">
	import PageHero from '$lib/components/PageHero.svelte';
	import PageIntro from '$lib/components/PageIntro.svelte';
	import Blocks from '$lib/components/Blocks.svelte';
	import type { Page } from '$lib/interfaces/page';

	let { page }: { page: Page } = $props();
</script>

<PageHero title={page.title} overtitle={page.overtitle} theme={page.theme} cover={page.cover} />

<PageIntro
	title={page.introTitle}
	text={page.intro}
	layout={page.introLayout}
	cta={page.introCta}
	parentPage={page.parentPage}
	theme={page.theme}
	titleImage={page.introTitleImage}
/>

<Blocks blocks={page.body} theme={page.theme} />
