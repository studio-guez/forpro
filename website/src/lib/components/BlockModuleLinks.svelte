<script lang="ts">
	import type { ModuleLinksContent, Theme } from '$lib/interfaces/page';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import { themeColors } from '$lib/utils/themeColors';

	interface Props {
		content: ModuleLinksContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const hasBackgroundImage = $derived(
		content.variant === 'backgroundImage' && !!content.backgroundImage
	);

	// The background-image variant always draws white on top of the photo.
	const colors = $derived(
		themeColors[theme][content.variant === 'inverted' ? 'inverted' : 'default']
	);
	const background = $derived(hasBackgroundImage ? null : colors.bg);
	const textColor = $derived(hasBackgroundImage ? 'var(--color-white)' : colors.text);

	// A white accent means the block sits on a coloured surface: the links are inverted onto it.
	const ctaInverted = $derived(hasBackgroundImage || colors.accent === 'var(--color-white)');
	const ctaColor = $derived(ctaInverted ? colors.bg : colors.accent);

	const uid = $props.id();
	const titleId = `module-links-title-${uid}`;
</script>

<section
	class="px-card py-12 md:py-16 rounded-3xl relative overflow-hidden"
	style:background-color={background}
	style:color={textColor}
	aria-labelledby={titleId}
>
	{#if hasBackgroundImage && content.backgroundImage}
		<Img
			image={content.backgroundImage}
			alt=""
			class="absolute inset-0 w-full h-full object-cover"
		/>
		<div class="absolute inset-0 bg-black/30"></div>
	{/if}

	<div
		class="relative z-1 flex flex-col gap-10 md:flex-row md:items-end md:justify-between md:gap-12"
	>
		<div class="md:max-w-2xl">
			<h2 id={titleId} class="text-h2">{content.title}</h2>
			{#if content.subtitle}
				<p class="text-body-1 mt-4">{content.subtitle}</p>
			{/if}
		</div>

		{#if content.links.length > 0}
			<ul class="flex flex-wrap gap-4 md:justify-end shrink-0">
				{#each content.links as link (link.url + link.label)}
					<li>
						<CtaLink cta={link} color={ctaColor} inverted={ctaInverted} />
					</li>
				{/each}
			</ul>
		{/if}
	</div>
</section>
