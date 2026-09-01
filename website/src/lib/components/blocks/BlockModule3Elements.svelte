<script lang="ts">
	import type { Module3ElementsContent, Theme } from '$lib/interfaces/page';
	import { getThemeColors } from '$lib/utils/themeColors';
	import { getThemeElementShapes } from '$lib/utils/themeShapes';
	import Card from '$lib/components/ui/Card.svelte';
	import ThreeElements from '$lib/components/ui/ThreeElements.svelte';

	interface Props {
		content: Module3ElementsContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const colors = $derived(getThemeColors(theme, content.variant));
	const inverted = $derived(content.variant === 'inverted');
	const shapes = $derived(getThemeElementShapes(theme));
</script>

{#if content.elements.length > 0}
	<Card
		background={colors.bg}
		color={colors.text}
		title={content.title}
		shortDesc={content.shortDesc}
		titleBackground={colors.titleBackground}
		titleColor={colors.title}
	>
		<div class:lg:pb-18={inverted}>
			<ThreeElements
				elements={content.elements}
				{shapes}
				blobColor={inverted ? colors.invertedElementsBlobColor : colors.bgContrast}
				textColor={inverted ? colors.invertedElementsTextColor : colors.surfaceText}
				decoColor={inverted ? colors.invertedElementsTextColor : colors.bgContrast}
				class="mt-12 lg:mt-6"
			/>
		</div>
	</Card>
{/if}
