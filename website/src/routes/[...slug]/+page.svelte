<script lang="ts">
	import PageHeader from '$lib/components/PageHeader.svelte';
	import PageIntro from '$lib/components/PageIntro.svelte';
	import type { PageData } from './$types';

	let { data }: { data: PageData } = $props();

	const page = $derived(data.page);

	console.log('page', page);
</script>

<svelte:head>
	<title>{page.seo.title}</title>
	{#if page.seo.description}
		<meta name="description" content={page.seo.description} />
	{/if}
	{#if page.seo.canonicalUrl}
		<link rel="canonical" href={page.seo.canonicalUrl} />
	{/if}
	{#if page.seo.robots}
		<meta name="robots" content={page.seo.robots} />
	{/if}

	{#if page.trackWithMatomo}
		<!-- Matomo -->
		<script>
			var _paq = (window._paq = window._paq || []);
			/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			(function () {
				var u = '//matomo.for-pro.ch/';
				_paq.push(['setTrackerUrl', u + 'matomo.php']);
				_paq.push(['setSiteId', '1']);
				var d = document,
					g = d.createElement('script'),
					s = d.getElementsByTagName('script')[0];
				g.async = true;
				g.src = u + 'matomo.js';
				s.parentNode.insertBefore(g, s);
			})();
		</script>
		<noscript
			><p>
				<img
					referrerpolicy="no-referrer-when-downgrade"
					src="//matomo.for-pro.ch/matomo.php?idsite=1&amp;rec=1"
					style="border:0;"
					alt=""
				/>
			</p></noscript
		>
		<!-- End Matomo Code -->
	{/if}
</svelte:head>

<PageHeader
	title={page.title}
	overtitle={page.overtitle}
	theme={page.theme}
	cover={page.cover}
/>

<PageIntro title={page.introTitle} text={page.intro} layout={page.introLayout} cta={page.introCta} parentPage={page.parentPage} theme={page.theme} titleImage={page.introTitleImage} />
