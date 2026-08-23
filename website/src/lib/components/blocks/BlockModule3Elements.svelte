<script lang="ts">
	import type { Module3ElementsContent, Theme } from '$lib/interfaces/page';
	import { cardThemeColors } from '$lib/utils/themeColors';
	import Card from '$lib/components/ui/Card.svelte';
	import ThreeElements from '$lib/components/ui/ThreeElements.svelte';

	interface Props {
		content: Module3ElementsContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	// The block has no variant of its own, it always uses the theme's default palette.
	const colors = $derived(cardThemeColors[theme].default);
</script>

{#if content.elements.length > 0}
	<Card
		background={colors.bg}
		color={colors.text}
		title={content.title}
		shortDesc={content.shortDesc}
		titleBackground="var(--color-white)"
		titleColor={colors.title}
	>
		<ThreeElements
			elements={content.elements}
			blobColor={colors.bgContrast}
			textColor={colors.text}
			decoColor={colors.bgContrast}
			class="mt-12 md:mt-6"
		/>
	</Card>
{/if}
