<script lang="ts">
	import type { ModuleTextContent, Theme } from '$lib/interfaces/page';
	import { getThemeColors } from '$lib/utils/themeColors';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';

	interface Props {
		content: ModuleTextContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const colors = $derived(getThemeColors(theme, content.variant));
</script>

<Card
	background={colors.bg}
	color={colors.text}
	title={content.title}
	hideTitle={content.hideTitle}
	titleVariant="pill"
	titleBackground={colors.titleBackground}
	titleColor={colors.title}
>
	{#if content.content}
		<div class="prose text-body-2 text-center mx-auto max-w-3xl mt-6 lg:mt-12">
			{@html content.content}
		</div>
	{/if}

	{#if content.ctas?.length}
		<div class="flex flex-wrap gap-4 justify-center md:justify-end mt-6 lg:mt-12">
			<!-- Keyed by index: several ctas can share the same url. -->
			{#each content.ctas as cta, i (i)}
				<CtaLink {cta} color={colors.accent} inverted={colors.onDark} size="lg" />
			{/each}
		</div>
	{/if}
</Card>
