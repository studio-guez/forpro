<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import type { ProjetCard } from '$lib/interfaces/page';

	interface Props {
		project: ProjetCard;
		headingTag?: string;
		/** Rendered slot width — the card is laid out by its parent, which owns the geometry. */
		sizes?: string;
	}

	let { project, headingTag = 'h3', sizes }: Props = $props();

	// A single badge over the cover: the first programme, or the first category as a fallback.
	const tags = $derived(
		(project.programs.length > 0 ? project.programs : project.categories).slice(0, 1)
	);
	const meta = $derived(
		[project.collectiveName, ...project.categories.map((category) => category.title)]
			.filter(Boolean)
			.join(' · ')
	);
</script>

<article>
	<a href={project.url} class="group block">
		<div class="relative rounded-2xl overflow-hidden aspect-16/9">
			{#if project.cover}
				<Img
					image={project.cover}
					alt={project.cover.alt ?? project.title}
					{sizes}
					class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
				/>
			{/if}
			<TermTags terms={tags} size="sm" class="absolute top-4 left-4" />
		</div>

		<svelte:element this={headingTag} class="text-h4 mt-5">{project.title}</svelte:element>
		{#if meta}
			<p class="text-label mt-1">{meta}</p>
		{/if}
	</a>
</article>
