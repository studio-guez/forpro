<script lang="ts">
	import type { HomeResourceCard } from '$lib/interfaces/home';
	import Img from '$lib/components/ui/Img.svelte';
	import IconArrow from '$lib/components/svg/IconArrow.svelte';
	import ShapeHomeResource1 from '$lib/components/svg/ShapeHomeResource1.svelte';
	import ShapeHomeResource2 from '$lib/components/svg/ShapeHomeResource2.svelte';
	import ShapeHomeResource3 from '$lib/components/svg/ShapeHomeResource3.svelte';
	import ShapeHomeProject from '$lib/components/svg/ShapeHomeProject.svelte';

	interface Props {
		resource: HomeResourceCard;
		/** Rank among the page cards (not the carousel slot): picks one of their 3 shapes. */
		index: number;
		headingTag?: string;
		/** Rendered slot width — the card is laid out by its parent, which owns the geometry. */
		sizes?: string;
		/** Off the active slot the card is decorative: keep it out of the tab order. */
		tabindex?: number;
	}

	let { resource, index, headingTag = 'h3', sizes, tabindex }: Props = $props();

	const pageShapes = [ShapeHomeResource1, ShapeHomeResource2, ShapeHomeResource3];

	// Pages are blue, projects orange; the shape sits in the lighter tint of each.
	const isProject = $derived(resource.type === 'project');
	const Shape = $derived(isProject ? ShapeHomeProject : pageShapes[index % pageShapes.length]);
	const background = $derived(isProject ? 'var(--color-orange)' : 'var(--color-blue)');
	const shapeColor = $derived(isProject ? 'var(--color-orange-light)' : 'var(--color-blue-light)');
</script>

<article class="h-full drop-shadow">
	<a
		href={resource.url}
		{tabindex}
		class="group relative flex flex-col h-full rounded-3xl overflow-hidden p-3 text-white"
		style:background-color={background}
	>
		<div class="relative rounded-2xl overflow-hidden aspect-4/3 sm:aspect-16/10 bg-white/10">
			{#if resource.cover}
				<Img
					image={resource.cover}
					alt={resource.cover.alt ?? resource.title}
					{sizes}
					class="w-full h-full object-cover transition-transform group-hover:scale-105"
				/>
			{/if}
		</div>

		<div
			class="absolute bottom-0 right-0 w-2/5 pointer-events-none"
			style:color={shapeColor}
		>
			<Shape class="w-full h-auto" />
		</div>

		<div class="relative flex-1 flex items-end justify-between gap-3 py-2 px-2 min-h-45" style:color={background}>
			<div class="min-w-0 text-white">
				{#if resource.overtitle}
					<p class="text-caption">{resource.overtitle}</p>
				{/if}
				<svelte:element this={headingTag} class="text-h4">{resource.title}</svelte:element>
			</div>
			<span
				class="shrink-0 w-10 h-10 lg:w-11 lg:h-11 rounded-full border-3 border-white text-white! flex items-center justify-center transition-colors group-hover:bg-white group-hover:text-current!"
				aria-hidden="true"
			>
				<IconArrow class="w-6 h-6" />
			</span>
		</div>
	</a>
</article>
