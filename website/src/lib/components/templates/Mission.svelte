<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import { PAGE, cell, toSizes } from '$lib/utils/imgSizes';
	import BackLink from '$lib/components/ui/BackLink.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import SingleContentFooter from '$lib/components/blocks/SingleContentFooter.svelte';
	import ApplicationsClosedNotice from '$lib/components/ui/ApplicationsClosedNotice.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import { formatShortDate, toDate } from '$lib/utils/date';
	import type { MissionPage } from '$lib/interfaces/missions';

	let { page }: { page: MissionPage } = $props();

	const coverSizes = toSizes(cell(PAGE, { 0: 1, 1024: 2 }, 4));

	const publishedDate = $derived(toDate(page.publishedDate));
	const missionDate = $derived(toDate(page.date));

	const sections = $derived([
		{ label: 'Ton profil :', html: page.profile },
		{ label: 'Tes missions :', html: page.tasks },
		{ label: 'Ton planning :', html: page.planning }
	]);
</script>

<article class="px-base space-y-16 lg:space-y-24">
	<header class="space-y-6">
		{#if page.parentPage}
			<BackLink parentPage={page.parentPage} />
		{/if}

		{#if !page.openToApplications}
			<ApplicationsClosedNotice
				message="Cette mission n'est plus ouverte aux candidatures. Elle reste consultable à titre informatif."
			/>
		{/if}

		<h1 class="text-h1 text-blue">Mission : {page.title}</h1>

		<p class="text-label text-grey-dark flex flex-wrap gap-x-12 gap-y-2">
			<span>
				Publiée le
				<time datetime={page.publishedDate}>
					{publishedDate ? formatShortDate(publishedDate) : page.publishedDate}
				</time>
			</span>
			<span>Annonceur : {page.announcer}</span>
		</p>

		<TermTags terms={page.categories} label="Catégories" />
	</header>

	<section aria-labelledby="mission-intro" class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-start">
		<div class="space-y-6">
			<h2 id="mission-intro" class="text-h3 text-black">{page.introTitle}</h2>
			<div class="prose text-body-2 text-grey-dark">{@html page.shortDesc}</div>
		</div>

		{#if page.cover}
			<Img
				image={page.cover}
				alt={page.cover.alt ?? page.title}
				sizes={coverSizes}
				class="w-full aspect-4/3 object-cover rounded-3xl"
			/>
		{/if}
	</section>

	<div class="space-y-10">
		{#each sections as section (section.label)}
			<section class="grid lg:grid-cols-3 gap-4 lg:gap-8">
				<h2 class="text-h4 text-black">{section.label}</h2>
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
