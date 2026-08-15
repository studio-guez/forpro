<script lang="ts">
	import type { CmsImage, PageCta, PageLayout, PageParent, Theme } from '$lib/interfaces/page';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';

	interface Props {
		title: string;
		text: string;
		layout?: PageLayout;
		cta?: PageCta | null;
		parentPage?: PageParent | null;
		theme?: Theme;
		titleImage?: CmsImage | null;
	}

	let { title, text, layout = '1col', cta = null, parentPage = null, theme = 'default', titleImage = null }: Props = $props();

	const colorByTheme: Record<Theme, string> = {
		default:        'blue',
		campus:         'blue',
		entreprendre:   'purple-light',
		projets_jeunes: 'orange',
		tremplin_jobs:  'purple-light',
		soutien:        'pink',
		cekale:         'purple',
		la_ref:         'pink',
		learninglab:    'green',
		foodlab:        'orange',
		grandlab:       'red',
		makerlab:       'grey-dark',
	};

	const themeColor = $derived(colorByTheme[theme] ?? 'blue');

	const isTwoCol = $derived(layout === '2col');
	const hasCta = $derived(!!cta?.label && !!cta?.url);
</script>

<section class="py-18 px-base relative" aria-labelledby="page-intro-title">
	{#if parentPage}
		<div class="mb-6 mt-[calc(-1.5rem-1lh)]">
			<a href="/{parentPage.path}" style:color="var(--color-{themeColor})" class="text-label group"><span class="inline-block group-hover:-translate-x-0.75 transition-transform">&larr;</span> Retour à {parentPage.title}</a>
		</div>
	{/if}
	{#if isTwoCol}
		<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
			<h2 id="page-intro-title" class="text-h4 max-lg:text-center">
				{#if titleImage}
					<img src={titleImage.url} srcset={titleImage.srcset} width={titleImage.width} height={titleImage.height} alt={title} class="max-lg:mx-auto max-w-80 max-h-40 object-contain" />
				{:else}
					{title}
				{/if}
			</h2>
			<div class="lg:col-span-2">
				<div class="prose max-lg:text-center text-grey-dark">
					{@html text}
				</div>
				{#if hasCta}
					<div class="max-lg:text-right mt-12">
						<CtaLink cta={cta!} color={themeColor} />
					</div>
				{/if}
			</div>
		</div>
	{:else}
		<h2 id="page-intro-title" class="text-h2 mb-12 text-center">
			{#if titleImage}
				<img src={titleImage.url} srcset={titleImage.srcset} width={titleImage.width} height={titleImage.height} alt={title} class="mx-auto max-w-80 max-h-40 object-contain" />
			{:else}
				{title}
			{/if}
		</h2>
		<div class="prose text-center text-grey-dark">
			{@html text}
		</div>
		{#if hasCta}
			<div class="text-right mt-12">
				<CtaLink cta={cta!} color={themeColor} />
			</div>
		{/if}
	{/if}
</section>
