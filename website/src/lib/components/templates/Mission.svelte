<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import BackLink from '$lib/components/ui/BackLink.svelte';
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import { formatShortDate, toDate } from '$lib/utils/date';
	import type { MissionPage } from '$lib/interfaces/missions';

	let { page }: { page: MissionPage } = $props();

	const publishedDate = $derived(toDate(page.publishedDate));
	const missionDate = $derived(toDate(page.date));

	const sections = $derived([
		{ label: 'Ton profil :', html: page.profile },
		{ label: 'Tes missions :', html: page.tasks },
		{ label: 'Ton planning :', html: page.planning }
	]);

	let shared = $state(false);

	// Native share sheet when available (mobile), clipboard fallback otherwise.
	const share = async (): Promise<void> => {
		const url = window.location.href;
		try {
			if (navigator.share) {
				await navigator.share({ title: `Mission : ${page.title}`, url });
				return;
			}
			await navigator.clipboard.writeText(url);
			shared = true;
			setTimeout(() => (shared = false), 3000);
		} catch {
			// The user dismissed the share sheet, or the clipboard is unavailable.
		}
	};
</script>

<article class="space-y-16 md:space-y-24">
	<header class="space-y-6">
		{#if page.parentPage}
			<BackLink parentPage={page.parentPage} />
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

		<TermTags terms={page.domains} label="Domaines" />
	</header>

	<section aria-labelledby="mission-intro" class="grid md:grid-cols-2 gap-8 md:gap-16 items-start">
		<div class="space-y-6">
			<h2 id="mission-intro" class="text-h3 text-black">{page.introTitle}</h2>
			<div class="prose text-body-2 text-grey-dark">{@html page.shortDesc}</div>
		</div>

		{#if page.cover}
			<Img
				image={page.cover}
				alt={page.cover.alt ?? page.title}
				sizes="(min-width: 768px) 50vw, 100vw"
				class="w-full aspect-4/3 object-cover rounded-3xl"
			/>
		{/if}
	</section>

	<div class="space-y-10">
		{#each sections as section (section.label)}
			<section class="grid md:grid-cols-3 gap-4 md:gap-8">
				<h2 class="text-h4 text-black">{section.label}</h2>
				<div class="prose text-body-2 md:col-span-2">{@html section.html}</div>
			</section>
		{/each}
	</div>

	<div class="flex flex-wrap items-center justify-end gap-4">
		<button
			type="button"
			class="text-label inline-flex items-center gap-2.5 rounded-full border-3 border-blue text-blue px-5.5 py-3 leading-none transition-colors hover:bg-blue hover:text-white"
			onclick={share}
		>
			{shared ? 'Lien copié !' : 'Partager'}
			<IconLink width={22} height={22} />
		</button>

		{#if page.applyCta}
			<a
				href={page.applyCta.url}
				class="text-label inline-flex items-center gap-2.5 rounded-full border-3 border-blue bg-blue text-white px-5.5 py-3 leading-none transition-colors hover:bg-transparent hover:text-blue"
			>
				{page.applyCta.label}
			</a>
		{/if}
	</div>

	{#if page.parentPage}
		<footer>
			<BackLink parentPage={page.parentPage} label="Retour aux missions" />
		</footer>
	{/if}
</article>
