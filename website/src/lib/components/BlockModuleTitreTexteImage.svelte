<script lang="ts">
	import type { ModuleTitreTexteImageContent, Theme } from '$lib/interfaces/page';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';

	interface Props {
		content: ModuleTitreTexteImageContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const bgColorByTheme: Record<Theme, string> = {
		default:        '',
		campus:         'blue',
		entreprendre:   'var(--color-purple-light)',
		projets_jeunes: 'var(--color-orange)',
		tremplin_jobs:  'var(--color-purple-light)',
		soutiens:       'var(--color-pink)',
		cekale:         'var(--color-purple)',
		la_ref:         'var(--color-pink)',
		learninglab:    'var(--color-teal)',
		foodlab:        'var(--color-orange)',
		grandlab:       'var(--color-red)',
		makerlab:       'var(--color-grey-dark)',
	};

	const textColorByTheme: Record<Theme, string> = {
		default:        '',
		campus:         'var(--color-white)',
		entreprendre:   'var(--color-white)',
		projets_jeunes: 'var(--color-white)',
		tremplin_jobs:  'var(--color-white)',
		soutiens:       'var(--color-white)',
		cekale:         'var(--color-white)',
		la_ref:         'var(--color-white)',
		learninglab:    'var(--color-white)',
		foodlab:        'var(--color-white)',
		grandlab:       'var(--color-white)',
		makerlab:       'var(--color-white)',
	};

	const bgColor = $derived(bgColorByTheme[theme] ?? '');
	const textColor = $derived(textColorByTheme[theme] ?? '');
	const isInversed = $derived(content.variant === 'inversé');
	const isImageLeft = $derived(content.imagePosition === 'left');
</script>

<section
	class="py-18 px-card rounded-3xl"
	style={[bgColor && `background-color: ${bgColor}`, textColor && `color: ${textColor}`].filter(Boolean).join('; ')}
>
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
		{#if content.image}
			<div class:lg:order-last={!isImageLeft} class:lg:order-first={isImageLeft}>
				<img
					src={content.image.url}
					srcset={content.image.srcset}
					width={content.image.width}
					height={content.image.height}
					alt={content.image.alt ?? ''}
					style:object-position={content.image.focus ?? 'center'}
					class="w-full h-auto rounded-2xl object-cover"
				/>
			</div>
		{/if}
		<div class:lg:order-first={!isImageLeft} class:lg:order-last={isImageLeft}>
			<h2 class="text-h3 mb-6">{content.title}</h2>
			<div class="prose text-body-2 mb-8">
				{@html content.description}
			</div>
			{#if content.cta}
				<CtaLink cta={content.cta} color={textColor} hoverColor={bgColor} />
			{/if}
		</div>
	</div>
</section>
