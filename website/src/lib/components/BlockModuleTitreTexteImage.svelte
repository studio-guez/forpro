<script lang="ts">
	import type { ModuleTitreTexteImageContent } from '$lib/interfaces/page';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';

	interface Props {
		content: ModuleTitreTexteImageContent;
	}

	let { content }: Props = $props();

	const isInversed = $derived(content.variant === 'inversé');
	const isImageLeft = $derived(content.imagePosition === 'left');
</script>

<section class="py-18 px-card" class:bg-black={isInversed} class:text-white={isInversed}>
	<div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
		{#if content.image}
			<div class:lg:order-last={!isImageLeft} class:lg:order-first={isImageLeft}>
				<img
					src={content.image.url}
					srcset={content.image.srcset}
					width={content.image.width}
					height={content.image.height}
					alt={content.image.alt ?? ''}
					style:object-position={content.image.focus ?? 'center'}
					class="w-full h-auto rounded-2xl object-cover"
				/>
			</div>
		{/if}
		<div class:lg:order-first={!isImageLeft} class:lg:order-last={isImageLeft}>
			<h2 class="text-h3 mb-6">{content.title}</h2>
			<div class="prose text-body-2 mb-8">
				{@html content.description}
			</div>
			{#if content.cta}
				<CtaLink cta={content.cta} />
			{/if}
		</div>
	</div>
</section>
