<script lang="ts">
	import BackLink from '$lib/components/ui/BackLink.svelte';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import BlockModuleTimeline from '$lib/components/blocks/BlockModuleTimeline.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import CardSmall2Cols from '$lib/components/ui/CardSmall2Cols.svelte';
	import { toDate } from '$lib/utils/date';
	import type { JobOfferPage } from '$lib/interfaces/jobOffers';

	let { page }: { page: JobOfferPage } = $props();

	const dateFormat = new Intl.DateTimeFormat('fr-CH', { dateStyle: 'long' });

	const terms = $derived(page.sectors);
	const deadline = $derived(toDate(page.deadline));

	// The maximum is only set when the rate is a range.
	const activityRate = $derived(
		page.activityRateMax === null || page.activityRateMax === page.activityRateMin
			? `${page.activityRateMin}%`
			: `${page.activityRateMin}% - ${page.activityRateMax}%`
	);

	const sections = $derived([
		{ label: 'Description :', html: page.description },
		{ label: 'Profil recherché :', html: page.profile },
		{ label: 'Conditions :', html: page.conditions }
	]);
</script>

<article class="space-y-16 lg:space-y-24">
	<header class="space-y-6">
		{#if page.parentPage}
			<BackLink parentPage={page.parentPage} />
		{/if}

		<h1 class="text-h1 text-blue">{page.title}</h1>

		<TermTags {terms} label="Catégories" />
	</header>

	<div class="space-y-10">
		{#each sections as section (section.label)}
			<section class="grid lg:grid-cols-3 gap-4 lg:gap-8">
				<h2 class="text-h4 text-blue">{section.label}</h2>
				<div class="prose text-body-2 lg:col-span-2">{@html section.html}</div>
			</section>
		{/each}
	</div>

	<CardSmall2Cols title="Informations :">
		{#snippet first()}
			<dl class="mt-6 space-y-4 text-body-2">
				<div>
					<dt class="text-label">Lieu de travail</dt>
					<dd>{page.location}</dd>
				</div>
				<div>
					<dt class="text-label">Taux d'activité</dt>
					<dd>{activityRate}</dd>
				</div>
				<div>
					<dt class="text-label">Entrée en fonction</dt>
					<dd>{page.startDate}</dd>
				</div>
				<div>
					<dt class="text-label">Délai de candidature</dt>
					<dd>
						<time datetime={page.deadline}
							>{deadline ? dateFormat.format(deadline) : page.deadline}</time
						>
					</dd>
				</div>
			</dl>
		{/snippet}

		{#snippet second()}
			<div>
				<h2 class="text-h4">Le dossier complet doit être adressé à :</h2>
				<a
					href="mailto:{page.applicationEmail}"
					class="text-body-2 underline underline-offset-4 mt-4 inline-block hover:opacity-70 transition"
				>
					{page.applicationEmail}
				</a>
			</div>

			{#if page.pdfOffer}
				<a
					href={page.pdfOffer.url}
					download={page.pdfOffer.filename}
					class="text-label inline-flex items-center gap-3 rounded-full border-2 border-white px-6 py-2 hover:bg-white hover:text-blue transition"
				>
					Télécharger le PDF de l'offre
					<IconArrow class="rotate-90 w-5 h-5" />
					<span class="sr-only"
						>({page.pdfOffer.extension.toUpperCase()}, {page.pdfOffer.size})</span
					>
				</a>
			{/if}
		{/snippet}
	</CardSmall2Cols>

	<section aria-labelledby="job-offer-apply" class="space-y-10">
		<h2 id="job-offer-apply" class="text-h3 text-center">
			<span class="inline-block bg-blue text-white rounded-2xl px-8 pt-2 pb-3.5"
				>Comment postuler ?</span
			>
		</h2>

		<div class="prose text-body-2 text-blue text-center max-w-3xl mx-auto">
			{@html page.applicationContent}
		</div>

		{#if page.applicationQuestions.length > 0}
			<ul class="space-y-6">
				{#each page.applicationQuestions as item, i (i)}
					<li
						class="bg-blue text-white rounded-3xl px-6 py-8 lg:px-12 lg:py-10 grid lg:grid-cols-3 gap-4 lg:gap-8"
					>
						<h3 class="text-h4">{item.question}</h3>
						<div class="prose text-body-2 lg:col-span-2">{@html item.answer}</div>
					</li>
				{/each}
			</ul>
		{/if}
	</section>

	<BlockModuleTimeline
		content={{ title: 'Étapes du recrutement', hideTitle: false, steps: page.recruitingSteps }}
	/>
</article>
