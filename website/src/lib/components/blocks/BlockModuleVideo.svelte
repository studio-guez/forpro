<script lang="ts">
	import type { ModuleVideoContent, Theme } from '$lib/interfaces/page';
	import { cardThemeColors } from '$lib/utils/themeColors';
	import Card from '$lib/components/ui/Card.svelte';
	import VideoPlayer from '$lib/components/ui/VideoPlayer.svelte';
	import ShapeCasesDefault1 from '$lib/components/svg/ShapeCasesDefault1.svelte';
	import ShapeCasesDefault2 from '$lib/components/svg/ShapeCasesDefault2.svelte';

	interface Props {
		content: ModuleVideoContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const colors = $derived(cardThemeColors[theme].default);
</script>

<Card
	background={colors.bg}
	color={colors.text}
	shapeLeft={ShapeCasesDefault1}
	shapeRight={ShapeCasesDefault2}
	shapeColor={colors.bgContrast}
	title={content.title}
	shortDesc={content.shortDesc}
	titleBackground="var(--color-white)"
	titleColor={colors.title}
>
	{#if content.video}
		<div class="overflow-hidden rounded-2xl mt-12">
			<VideoPlayer src={content.video.url} />
		</div>
	{/if}

	{#if content.content}
		<div class="prose text-body-2 mt-8 text-center mx-auto max-w-3xl">
			{@html content.content}
		</div>
	{/if}
</Card>
