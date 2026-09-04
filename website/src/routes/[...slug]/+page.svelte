<script lang="ts">
	import Page from '$lib/components/templates/Page.svelte';
	import BasicPage from '$lib/components/templates/BasicPage.svelte';
	import Faq from '$lib/components/templates/Faq.svelte';
	import Event from '$lib/components/templates/Event.svelte';
	import Events from '$lib/components/templates/Events.svelte';
	import Project from '$lib/components/templates/Project.svelte';
	import Projects from '$lib/components/templates/Projects.svelte';
	import Team from '$lib/components/templates/Team.svelte';
	import JobOffer from '$lib/components/templates/JobOffer.svelte';
	import JobOffers from '$lib/components/templates/JobOffers.svelte';
	import Mission from '$lib/components/templates/Mission.svelte';
	import Missions from '$lib/components/templates/Missions.svelte';
	import Impressum from '$lib/components/templates/Impressum.svelte';
	import Press from '$lib/components/templates/Press.svelte';
	import FactoryLab from '$lib/components/templates/FactoryLab.svelte';
	import JsonLd from '$lib/components/layout/JsonLd.svelte';
	import type { PageData } from './$types';
	import { IS_PROD } from '$lib/env';
	import { page as currentPage } from '$app/state';
	import { cookieConsent } from '$lib/utils/cookieConsent.svelte';
	import { trackPageView } from '$lib/utils/matomo';

	let { data }: { data: PageData } = $props();

	const page = $derived(data.page);

	// The CMS resolves the Open Graph cascade (page -> parent -> site), but only
	// over the fields an editor filled in: the meta title and description are the
	// sensible fallbacks for the ones left empty.
	const seo = $derived(page.seo);
	const ogTitle = $derived(seo.ogTitle || seo.title);
	const ogDescription = $derived(seo.ogDescription || seo.description);

	// Markdown twin of this page, for readers that would rather not parse the
	// HTML. The home page's canonical ends in a slash, where `.md` alone would
	// make a dotfile path.
	const markdownUrl = $derived(
		seo.canonicalUrl.endsWith('/') ? `${seo.canonicalUrl}index.md` : `${seo.canonicalUrl}.md`
	);

	const trackable = $derived(IS_PROD && page.seo.trackWithMatomo);

	$effect(() => {
		if (trackable && cookieConsent.performance) {
			trackPageView(currentPage.url.href);
		}
	});
</script>

<svelte:head>
	<title>{seo.title}</title>
	{#if seo.description}
		<meta name="description" content={seo.description} />
	{/if}
	{#if seo.canonicalUrl}
		<link rel="canonical" href={seo.canonicalUrl} />
	{/if}
	{#if seo.robots && IS_PROD}
		<meta name="robots" content={seo.robots} />
	{/if}

	{#if seo.canonicalUrl}
		<link rel="alternate" type="text/markdown" href={markdownUrl} />
	{/if}

	<!-- Open Graph: what a link to this page looks like once it is shared. -->
	<meta property="og:type" content={seo.ogType || 'website'} />
	<meta property="og:title" content={ogTitle} />
	{#if ogDescription}
		<meta property="og:description" content={ogDescription} />
	{/if}
	{#if seo.canonicalUrl}
		<meta property="og:url" content={seo.canonicalUrl} />
	{/if}
	{#if seo.ogSiteName}
		<meta property="og:site_name" content={seo.ogSiteName} />
	{/if}
	{#if seo.locale}
		<meta property="og:locale" content={seo.locale} />
	{/if}
	{#if seo.ogImage}
		<meta property="og:image" content={seo.ogImage} />
	{/if}

	<!-- Twitter/X reads the Open Graph tags, except for the card format and the
		 image, which it wants under its own names. -->
	<meta name="twitter:card" content={seo.twitterCardType || 'summary_large_image'} />
	{#if seo.ogImage}
		<meta name="twitter:image" content={seo.ogImage} />
	{/if}
</svelte:head>

<!-- WebPage, breadcrumb, and the entity this template is about (Event,
	 JobPosting, FAQ, ...) — all assembled by the CMS. -->
<JsonLd schemas={seo.schemas} />

{#if page.template === 'basic-page'}
	<BasicPage {page} />
{:else if page.template === 'faq'}
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
{:else if page.template === 'job-offer'}
	<JobOffer {page} />
{:else if page.template === 'job-offers'}
	<JobOffers {page} />
{:else if page.template === 'mission'}
	<Mission {page} />
{:else if page.template === 'missions'}
	<Missions {page} />
{:else if page.template === 'impressum'}
	<Impressum {page} />
{:else if page.template === 'press'}
	<Press {page} />
{:else if page.template === 'factory-lab'}
	<FactoryLab {page} />
{:else}
	<Page {page} />
{/if}
