<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import type { ResourceCard } from '$lib/interfaces/page';

	interface Props {
		resource: ResourceCard;
		headingTag?: string;
	}

	let { resource, headingTag = 'h3' }: Props = $props();
</script>

<article>
	<div class="relative rounded-2xl overflow-hidden aspect-16/9">
		{#if resource.image}
			<Img
				image={resource.image}
				alt={resource.image.alt ?? resource.title}
				sizes="(min-width: 768px) 33vw, 80vw"
				class="w-full h-full object-cover"
			/>
		{/if}
	</div>

	<svelte:element this={headingTag} class="text-h4 mt-5">{resource.title}</svelte:element>
	{#if resource.shortDesc}
		<div class="prose text-body-2 mt-3">
			{@html resource.shortDesc}
		</div>
	{/if}
</article>
