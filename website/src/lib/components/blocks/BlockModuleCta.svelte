<script lang="ts">
	import type { ModuleCtaContent } from '$lib/interfaces/page';
	import CardSmall from '$lib/components/ui/CardSmall.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';

	interface Props {
		content: ModuleCtaContent;
	}

	let { content }: Props = $props();

	const hasBackgroundImage = $derived(
		content.variant === 'backgroundImage' && !!content.backgroundImage
	);

	const onDark = $derived(hasBackgroundImage || content.variant === 'default');
	const background = $derived(
		hasBackgroundImage ? null : onDark ? 'var(--color-blue)' : 'var(--color-white)'
	);
	const textColor = $derived(onDark ? 'var(--color-white)' : 'var(--color-blue)');
</script>

<CardSmall
	title={content.title}
	subtitle={content.subtitle}
	{background}
	color={textColor}
	backgroundImage={hasBackgroundImage ? content.backgroundImage : null}
>
	{#if content.links.length > 0}
		<ul class="flex flex-wrap gap-3 justify-end shrink-0 max-w-full">
			{#each content.links as link (link.url + link.label)}
				<li>
					<CtaLink cta={link} color="var(--color-blue)" inverted={onDark} />
				</li>
			{/each}
		</ul>
	{/if}
</CardSmall>
