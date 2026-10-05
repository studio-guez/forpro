<script lang="ts">
	import BackLink from '$lib/components/ui/BackLink.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import EventProjectHeader from '$lib/components/blocks/EventProjectHeader.svelte';
	import SingleContentFooter from '$lib/components/blocks/SingleContentFooter.svelte';
	import ApplicationsClosedNotice from '$lib/components/ui/ApplicationsClosedNotice.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import type { MissionPage } from '$lib/interfaces/missions';

	let { page }: { page: MissionPage } = $props();

	const sections = $derived([
		{ label: 'Ton profil :', html: page.profile },
		{ label: 'Tes missions :', html: page.tasks },
		{ label: 'Ton planning :', html: page.planning }
	]);
</script>

<article class="space-y-16 lg:space-y-24">
	<EventProjectHeader
		title="Mission : {page.title}"
		subtitle={page.introTitle}
		shortDesc={page.shortDesc}
		cover={page.cover}
		variant="mission"
	>
		{#snippet before()}
			{#if page.parentPage}
				<BackLink parentPage={page.parentPage} />
			{/if}

			{#if !page.openToApplications}
				<ApplicationsClosedNotice
					message="Cette mission n'est plus ouverte aux candidatures. Elle reste consultable à titre informatif."
				/>
			{/if}
		{/snippet}

		{#snippet meta()}
			<p class="text-label text-black flex flex-wrap gap-x-12 gap-y-2 mt-4.5 lg:mt-3">
				{#if page.date}
					<span>{page.date}</span>
				{/if}
				<span>Annonceur : {page.announcer}</span>
			</p>

			<TermTags terms={page.categories} label="Catégories" class="mt-4.5" />
		{/snippet}
	</EventProjectHeader>

	<div class="px-base space-y-10">
		{#each sections as section (section.label)}
			<section class="grid lg:grid-cols-3 gap-4 lg:gap-8">
				<h2 class="text-body-2 font-bold text-black">{section.label}</h2>
				<div class="prose text-body-2 lg:col-span-2">{@html section.html}</div>
			</section>
		{/each}
	</div>

	<SingleContentFooter
		parentPage={page.parentPage}
		title="Mission : {page.title}"
		backLabel="Retour aux missions"
	>
		{#snippet actions()}
			{#if page.openToApplications && page.applyCta}
				<CtaLink cta={page.applyCta} />
			{/if}
		{/snippet}
	</SingleContentFooter>
</article>
