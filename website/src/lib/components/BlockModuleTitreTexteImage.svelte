<script lang="ts">
	import type { ModuleTitreTexteImageContent, Theme, Variant } from '$lib/interfaces/page';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';

	interface Props {
		content: ModuleTitreTexteImageContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	type ThemeColors = { bg: string; text: string; accent: string; ctaHoverColor: string };

	const themeConfig: Record<Theme, Record<Variant, ThemeColors>> = {
		default: {
			default: { 
				bg: 'var(--color-purple)',
				text: 'var(--color-white)',        
				accent: 'var(--color-white)',        
				ctaHoverColor: 'var(--color-black)'      
			},
			inverted: { 
				bg: 'var(--bg-grey-dark)',                         
				text: 'var(--bg-grey-dark)',                          
				accent: 'var(--color-blue)',         
				ctaHoverColor: 'var(--color-white)'    
			},
		},
		campus: {
			default: {
				bg: 'var(--color-blue)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-blue)',
			},
			inverted: {
				bg: 'var(--color-white)',
				text: 'var(--color-blue)',
				accent: 'var(--color-blue)',
				ctaHoverColor: 'var(--color-white)',
			},
		},
		entreprendre: {
			default: {
				bg: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-purple-light)',
			},
			inverted: {
				bg: 'var(--color-white)',
				text: 'var(--color-black)',
				accent: 'var(--color-purple-light)',
				ctaHoverColor: 'var(--color-white)',
			},
		},
		projets_jeunes: {
			default: {
				bg: 'var(--color-orange)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-orange)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				text: 'var(--color-orange)',
				accent: 'var(--color-orange)',
				ctaHoverColor: 'var(--color-orange-light)',
			},
		},
		tremplin_jobs: {
			default: {
				bg: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-purple-light)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				text: 'var(--color-purple-light)',
				accent: 'var(--color-purple-light)',
				ctaHoverColor: 'var(--color-purple)',
			},
		},
		soutiens: {
			default: {
				bg: 'var(--color-pink)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-pink)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				text: 'var(--color-pink)',
				accent: 'var(--color-pink)',
				ctaHoverColor: 'var(--color-purple)',
			},
		},
		cekale: {
			default: {
				bg: 'var(--color-purple)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-purple)',
			},
			inverted: {
				bg: 'var(--color-purple-pale)',
				text: 'var(--color-purple)',
				accent: 'var(--color-purple)',
				ctaHoverColor: 'var(--color-purple-pale)',
			},
		},
		la_ref: {
			default: {
				bg: 'var(--color-pink)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-pink)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				text: 'var(--color-pink)',
				accent: 'var(--color-pink)',
				ctaHoverColor: 'var(--color-purple)',
			},
		},
		learninglab: {
			default: {
				bg: 'var(--color-teal)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-teal)',
			},
			inverted: {
				bg: 'var(--color-teal-light)',
				text: 'var(--color-teal)',
				accent: 'var(--color-teal)',
				ctaHoverColor: 'var(--color-teal-light)',
			},
		},
		foodlab: {
			default: {
				bg: 'var(--color-orange)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-orange)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				text: 'var(--color-orange)',
				accent: 'var(--color-orange)',
				ctaHoverColor: 'var(--color-orange-light)',
			},
		},
		grandlab: {
			default: {
				bg: 'var(--color-red)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-red)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				text: 'var(--color-red)',
				accent: 'var(--color-red)',
				ctaHoverColor: 'var(--color-orange-light)',
			},
		},
		makerlab: {
			default: {
				bg: 'var(--color-grey-dark)',
				text: 'var(--color-white)',
				accent: 'var(--color-white)',
				ctaHoverColor: 'var(--color-grey-dark)',
			},
			inverted: {
				bg: 'var(--color-grey-light)',
				text: 'var(--color-grey-dark)',
				accent: 'var(--color-grey-dark)',
				ctaHoverColor: 'var(--color-grey-light)',
			},
		},
	};

	const colors = $derived(themeConfig[theme][content.variant]);
	const isImageLeft = $derived(content.imagePosition === 'left');
	const hasBackground = $derived(!!colors.bg && colors.bg !== 'transparent' && colors.bg !== 'var(--color-white)');

	const uid = $props.id();
	const titleId = `module-titre-texte-image-${uid}`;
</script>

<section
	class="px-card rounded-3xl"
	class:pt-12={hasBackground}
	class:pb-18={hasBackground}
	style={[colors.bg && `background-color: ${colors.bg}`, colors.text && `color: ${colors.text}`].filter(Boolean).join('; ')}
	aria-labelledby={titleId}
>
	<h2 id={titleId} class="text-h2 text-center mb-12" style={colors.accent && `color: ${colors.accent}`}>{content.title}</h2>
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
		{#if content.image}
			<div class="overflow-hidden rounded-2xl [contain:size]" class:lg:order-last={!isImageLeft}>
				<img
					src={content.image.url}
					srcset={content.image.srcset}
					width={content.image.width}
					height={content.image.height}
					alt={content.image.alt ?? ''}
					style:object-position={content.image.focus ?? 'center'}
					class="w-full h-full object-cover"
				/>
			</div>
		{/if}
		<div>
			<div class="prose text-body-2 mb-12">
				{@html content.description}
			</div>
			{#if content.cta}
				<CtaLink cta={content.cta} color={colors.accent} hoverColor={colors.ctaHoverColor} />
			{/if}
		</div>
	</div>
</section>
