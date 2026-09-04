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
	import type { PageData } from './$types';
	import { IS_PROD } from '$lib/env';
	import { page as currentPage } from '$app/state';
	import { cookieConsent } from '$lib/utils/cookieConsent.svelte';
	import { trackPageView } from '$lib/utils/matomo';

	let { data }: { data: PageData } = $props();

	const page = $derived(data.page);

	const trackable = $derived(
		IS_PROD && page.trackWithMatomo
	);

	$effect(() => {
		if (trackable && cookieConsent.performance) {
			trackPageView(currentPage.url.href);
		}
	});
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
</svelte:head>

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
