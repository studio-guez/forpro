<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import { termColor } from '$lib/utils/shared';
	import type { ProjetCard } from '$lib/interfaces/page';

	interface Props {
		project: ProjetCard;
		headingTag?: string;
	}

	let { project, headingTag = 'h3' }: Props = $props();

	const tag = $derived(project.themes[0] ?? project.types[0] ?? null);
	const meta = $derived(
		[project.collectiveName, ...project.types.map((type) => type.title)].filter(Boolean).join(' · ')
	);
</script>

<a href={project.url} class="group block">
	<div class="relative rounded-2xl overflow-hidden aspect-16/9">
		{#if project.cover}
			<Img
				image={project.cover}
				alt={project.cover.alt ?? project.title}
				sizes="(min-width: 768px) 50vw, 80vw"
				class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
			/>
		{/if}
		{#if tag}
			<span
				class="absolute top-4 left-4 text-caption text-white rounded-full px-3 py-0.5 leading-tight"
				style="background-color: {termColor(tag)}">{tag.title}</span
			>
		{/if}
	</div>

	<svelte:element this={headingTag} class="text-h4 mt-5">{project.title}</svelte:element>
	{#if meta}
		<p class="text-label mt-1">{meta}</p>
	{/if}
</a>
