<script lang="ts">
	import type { ModuleTextContent, Theme } from '$lib/interfaces/page';
	import { cardThemeColors } from '$lib/utils/themeColors';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';

	interface Props {
		content: ModuleTextContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const colors = $derived(cardThemeColors[theme][content.variant]);

	// White text means the block is drawn on a coloured background: the ctas are inverted onto it.
	const ctaInverted = $derived(colors.text === 'var(--color-white)');
	const ctaColor = $derived(ctaInverted ? colors.bg : colors.text);
</script>

<Card
	background={colors.bg}
	color={colors.text}
	title={content.title}
	hideTitle={content.hideTitle}
	titleVariant="plain"
	titleColor={colors.title}
>
	{#if content.content}
		<div class="prose text-body-2 text-center mx-auto max-w-3xl">
			{@html content.content}
		</div>
	{/if}

	{#if content.ctas?.length}
		<div class="flex flex-wrap gap-4 justify-center md:justify-end mt-8">
			{#each content.ctas as cta (cta.url)}
				<CtaLink {cta} color={ctaColor} inverted={ctaInverted} />
			{/each}
		</div>
	{/if}
</Card>
