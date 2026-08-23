<script lang="ts">
	import Page from '$lib/components/Page.svelte';
	import Faq from '$lib/components/Faq.svelte';
	import Event from '$lib/components/Event.svelte';
	import Events from '$lib/components/Events.svelte';
	import Project from '$lib/components/Project.svelte';
	import Projects from '$lib/components/Projects.svelte';
	import Team from '$lib/components/Team.svelte';
	import JobOffers from '$lib/components/JobOffers.svelte';
	import type { PageData } from './$types';
	import { IS_PROD } from '$lib/env';

	let { data }: { data: PageData } = $props();

	const page = $derived(data.page);
</script>

<svelte:head>
	<title>{page.seo.title}</title>
	{#if page.seo.description}
		<meta name="description" content={page.seo.description} />
	{/if}
	{#if page.seo.canonicalUrl}
		<link rel="canonical" href={page.seo.canonicalUrl} />
	{/if}
	{#if page.seo.robots && IS_PROD}
		<meta name="robots" content={page.seo.robots} />
	{/if}

	{#if page.template === 'page' && page.trackWithMatomo && IS_PROD}
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

{#if page.template === 'faq'}
	<Faq {page} />
{:else if page.template === 'event'}
	<Event {page} />
{:else if page.template === 'events'}
	<Events {page} />
{:else if page.template === 'project'}
	<Project {page} />
{:else if page.template === 'projects'}
	<Projects {page} />
{:else if page.template === 'team'}
	<Team {page} />
{:else if page.template === 'job-offers'}
	<JobOffers {page} />
{:else}
	<Page {page} />
{/if}
