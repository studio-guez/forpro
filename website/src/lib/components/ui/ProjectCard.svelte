<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import TermTags from '$lib/components/ui/TermTags.svelte';
	import type { ProjetCard, Variant } from '$lib/interfaces/page';

	interface Props {
		project: ProjetCard;
		headingTag?: string;
		/** Background the card sits on: `default` is the orange fill, `inverted` the light one. */
		variant?: Variant;
		/** Rendered slot width — the card is laid out by its parent, which owns the geometry. */
		sizes?: string;
	}

	let { project, headingTag = 'h3', variant = 'default', sizes }: Props = $props();

	// Below lg the copy is overlaid on the cover and stays white whatever the block sits on;
	// from lg it flows under the cover and takes the variant colour.
	const textColor = $derived(variant === 'inverted' ? 'lg:text-orange' : 'lg:text-white');

	const meta = $derived(
		[project.collectiveName, ...project.categories.map((category) => category.title)]
			.filter(Boolean)
			.join(' · ')
	);
</script>

<article>
	<a href={project.url} class="group block relative">
		<div class="relative rounded-2xl overflow-hidden aspect-4/3">
			{#if project.cover}
				<Img
					image={project.cover}
					alt={project.cover.alt ?? project.title}
					{sizes}
					class="w-full h-full object-cover transition-transform group-hover:scale-110"
				/>
			{/if}
			<div
				class="absolute inset-0 opacity-0 transition-opacity group-hover:opacity-30 bg-orange"
			></div>
			<div class="lg:hidden absolute inset-0 bg-linear-to-b from-black/15 via-black/0 to-black/30 group-hover:opacity-30 transition-opacity"></div>
			<TermTags
				terms={project.programs}
				label="Programmes"
				size="sm"
				class="absolute top-5 left-5 lg:top-6 lg:left-6"
			/>
		</div>

		<div class={['absolute inset-x-0 bottom-0 p-5 text-white lg:static lg:mt-3 lg:p-0', textColor]}>
			<svelte:element this={headingTag} class="text-h4">{project.title}</svelte:element>
			{#if meta}
				<p class="text-label mt-1">{meta}</p>
			{/if}
		</div>
	</a>
</article>
