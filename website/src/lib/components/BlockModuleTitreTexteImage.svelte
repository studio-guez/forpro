<script lang="ts">
	import type { ModuleTitreTexteImageContent, Theme, Variant } from '$lib/interfaces/page';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import Img from '$lib/components/ui/Img.svelte';

	interface Props {
		content: ModuleTitreTexteImageContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	type ThemeColors = { bg: string; text: string; accent: string };

	const themeConfig: Record<Theme, Record<Variant, ThemeColors>> = {
		default: {
			default: { 
				bg: 'var(--color-blue)',
				text: 'var(--color-white)',        
				accent: 'var(--color-white)',        
			},
			inverted: { 
				bg: 'var(--bg-grey-dark)',                         
				text: 'var(--bg-grey-dark)',                          
				accent: 'var(--color-blue)',         
			},
		},
		campus: {
			default: {
				bg: 'var(--color-blue)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-white)',
				text: 'var(--color-blue)',
				accent: 'var(--color-blue)',
			},
		},
		entreprendre: {
			default: {
				bg: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-white)',
				text: 'var(--color-black)',
				accent: 'var(--color-purple-light)',
			},
		},
		projets_jeunes: {
			default: {
				bg: 'var(--color-orange)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				text: 'var(--color-orange)',
				accent: 'var(--color-orange)',
			},
		},
		tremplin_jobs: {
			default: {
				bg: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				text: 'var(--color-purple-light)',
				accent: 'var(--color-purple-light)',
			},
		},
		soutiens: {
			default: {
				bg: 'var(--color-pink)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				text: 'var(--color-pink)',
				accent: 'var(--color-pink)',
			},
		},
		cekale: {
			default: {
				bg: 'var(--color-purple)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-purple-pale)',
				text: 'var(--color-purple)',
				accent: 'var(--color-purple)',
			},
		},
		la_ref: {
			default: {
				bg: 'var(--color-pink)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				text: 'var(--color-pink)',
				accent: 'var(--color-pink)',
			},
		},
		learninglab: {
			default: {
				bg: 'var(--color-teal)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-teal-light)',
				text: 'var(--color-teal)',
				accent: 'var(--color-teal)',
			},
		},
		foodlab: {
			default: {
				bg: 'var(--color-orange)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				text: 'var(--color-orange)',
				accent: 'var(--color-orange)',
			},
		},
		grandlab: {
			default: {
				bg: 'var(--color-red)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				text: 'var(--color-red)',
				accent: 'var(--color-red)',
			},
		},
		makerlab: {
			default: {
				bg: 'var(--color-grey-dark)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-grey-light)',
				text: 'var(--color-grey-dark)',
				accent: 'var(--color-grey-dark)',
			},
		},
	};

	const colors = $derived(themeConfig[theme][content.variant]);
	const isImageLeft = $derived(content.imagePosition === 'left');

	// A white accent means the block is drawn on a coloured background: the cta is inverted onto it.
	const ctaInverted = $derived(colors.accent === 'var(--color-white)');
	const ctaColor = $derived(ctaInverted ? colors.bg : colors.accent);
</script>

<Card
	title={content.title}
	titleVariant="plain"
	titleColor={colors.accent}
	background={colors.bg}
	color={colors.text}
>
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
		{#if content.image}
			<div class="overflow-hidden rounded-2xl [contain:size]" class:lg:order-last={!isImageLeft}>
				<Img image={content.image} class="w-full h-full object-cover" />
			</div>
		{/if}
		<div>
			<div class="prose text-body-2 mb-12">
				{@html content.description}
			</div>
			{#if content.cta}
				<CtaLink cta={content.cta} color={ctaColor} inverted={ctaInverted} />
			{/if}
		</div>
	</div>
</Card>
