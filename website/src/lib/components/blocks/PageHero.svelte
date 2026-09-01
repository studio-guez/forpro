<script lang="ts">
	import type { CmsImage, Theme } from '$lib/interfaces/page';
	import Img from '$lib/components/ui/Img.svelte';
	import ArrowOvertitle from '$lib/components/svg/ArrowOvertitle.svelte';

	interface Props {
		title: string;
		overtitle?: string | null;
		theme?: Theme;
		cover?: CmsImage | null;
	}

	let { title, overtitle = null, theme = 'default', cover = null }: Props = $props();

	const arrowFillByTheme: Record<Theme, string> = {
		default:        'fill-blue',
		campus:         'fill-blue',
		entreprendre:   'fill-purple-light',
		projets_jeunes: 'fill-orange',
		tremplin_jobs:  'fill-purple-light',
		soutiens:        'fill-pink',
		cekale:         'fill-purple',
		la_ref:         'fill-pink',
		learninglab:    'fill-teal',
		foodlab:        'fill-orange',
		grandlab:       'fill-red',
		makerlab:       'fill-white',
		factorylab:     'fill-teal',
	};

	const arrowFill = $derived(arrowFillByTheme[theme] ?? 'fill-white');
</script>

<section class="px-5 lg:px-9" aria-labelledby="page-title">
	<div class="min-h-138 relative text-white bg-grey-light rounded-3xl overflow-hidden flex">
		<div class="w-full bg-linear-to-t from-black/20 relative z-1 flex flex-col justify-end py-6 px-8">
			{#if overtitle}
			<div>
				<p class="inline-block text-h4 -mb-2">{overtitle}</p>
				<ArrowOvertitle fill={arrowFill} class="inline-block absolute -translate-y-1/2 ml-1 w-32.5 h-16" />
			</div>
			{/if}
			<h1 id="page-title" class="text-h0">
				{title}
			</h1>
		</div>

	{#if cover}
		<Img
			image={cover}
			sizes="100vw"
			class="absolute inset-0 h-full w-full object-cover"
			loading="eager"
			fetchpriority="high"
		/>
	{/if}
</section>
