<script lang="ts">
	import type { ModuleProjetsContent } from '$lib/interfaces/page';
	import Card from '$lib/components/ui/Card.svelte';
	import Carousel from '$lib/components/ui/Carousel.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import ProjectCard from '$lib/components/ui/ProjectCard.svelte';
	import ShapeProjets1 from '$lib/components/svg/ShapeProjets1.svelte';
	import ShapeProjets2 from '$lib/components/svg/ShapeProjets2.svelte';

	interface Props {
		content: ModuleProjetsContent;
	}

	let { content }: Props = $props();

	// The block is always orange; the default variant is filled (orange bg), inverted is light.
	const colors = { main: 'var(--color-orange)', deco: 'var(--color-orange-light)' };

	const filled = $derived(content.variant !== 'inverted');
</script>

{#if content.projects.length > 0}
<Card
	title={content.title}
	shortDesc={content.shortDesc}
	titleBackground={filled ? 'var(--color-white)' : colors.main}
	titleColor={filled ? colors.main : 'var(--color-white)'}
	background={filled ? colors.main : null}
	color={filled ? 'var(--color-white)' : colors.main}
	shapeLeft={filled ? ShapeProjets1 : null}
	shapeRight={filled ? ShapeProjets2 : null}
	shapeRightClasses="absolute bottom-0 right-0 translate-1/6 w-2/5 rotate-180"
	shapeColor={colors.deco}
>
	<Carousel
		items={content.projects}
		label={content.title}
		color={colors.main}
		inverted={filled}
		itemClass="w-4/5 md:w-[calc((100%-1.5rem)/2)]"
		class="mt-12"
	>
		{#snippet item(project)}
			<ProjectCard {project} />
		{/snippet}
	</Carousel>

	{#if content.cta}
		<div class="flex justify-center md:justify-end mt-8">
			<CtaLink cta={content.cta} color={colors.main} inverted={filled} />
		</div>
	{/if}
</Card>
{/if}
