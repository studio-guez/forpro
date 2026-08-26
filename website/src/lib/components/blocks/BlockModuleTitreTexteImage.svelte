<script lang="ts">
	import type { ModuleTitreTexteImageContent, Theme } from '$lib/interfaces/page';
	import { getThemeColors } from '$lib/utils/themeColors';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import Img from '$lib/components/ui/Img.svelte';

	interface Props {
		content: ModuleTitreTexteImageContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const colors = $derived(getThemeColors(theme, content.variant));
	const isImageLeft = $derived(content.imagePosition === 'left');
</script>

<Card
	title={content.title}
	titleVariant="plain"
	titleColor={colors.text}
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
				<CtaLink cta={content.cta} color={colors.accent} inverted={colors.onDark} />
			{/if}
		</div>
	</div>
</Card>
