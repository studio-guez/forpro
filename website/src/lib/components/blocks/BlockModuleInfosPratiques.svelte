<script lang="ts">
	import type { ModuleInfosPratiquesContent } from '$lib/interfaces/page';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import FaqQuestion from '$lib/components/ui/FaqQuestion.svelte';
	import ShapeInfosPratiques1 from '$lib/components/svg/ShapeInfosPratiques1.svelte';
	import ShapeInfosPratiques2 from '$lib/components/svg/ShapeInfosPratiques2.svelte';
	import ShapeInfosPratiques3 from '$lib/components/svg/ShapeInfosPratiques3.svelte';
	import ShapeInfosPratiquesDeco1 from '$lib/components/svg/ShapeInfosPratiquesDeco1.svelte';
	import ShapeInfosPratiquesDeco2 from '$lib/components/svg/ShapeInfosPratiquesDeco2.svelte';
	import ShapeInfosPratiquesDeco3 from '$lib/components/svg/ShapeInfosPratiquesDeco3.svelte';

	interface Props {
		content: ModuleInfosPratiquesContent;
	}

	let { content }: Props = $props();

	// The block is always teal; the default variant is filled (teal bg), inverted is light.
	const colors = { main: 'var(--color-teal)', blob: 'var(--color-teal-light)' };

	const filled = $derived(content.variant !== 'inverted');
	const textColor = $derived(filled ? 'var(--color-white)' : colors.main);
	const decoColor = $derived(filled ? colors.blob : colors.main);

	// One organic shape per element.
	const shapes = [ShapeInfosPratiques1, ShapeInfosPratiques2, ShapeInfosPratiques3];
	const rotations = [-8, -6, -8];

	const uid = $props.id();
</script>

{#if content.elements.length > 0 || content.faqs.length > 0}
<Card
	title={content.title}
	subtitle={content.subtitle}
	titleBackground={filled ? 'var(--color-white)' : colors.main}
	titleColor={filled ? colors.main : 'var(--color-white)'}
	background={filled ? colors.main : null}
	color={filled ? 'var(--color-white)' : colors.main}
>
	{#if content.elements.length > 0}
		<div class="relative mt-12 md:mt-6 grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-6 items-start">
			<div class="hidden md:block absolute left-[36%] top-[8%] w-32 lg:w-40" style:color={decoColor}>
				<ShapeInfosPratiquesDeco2 class="w-full h-auto" />
			</div>
			<div class="hidden md:block absolute left-[4%] bottom-[-6%] w-44 lg:w-56" style:color={decoColor}>
				<ShapeInfosPratiquesDeco1 class="w-full h-auto" />
			</div>
			<div class="hidden md:block absolute right-[4%] bottom-[-4%] w-28 lg:w-36" style:color={decoColor}>
				<ShapeInfosPratiquesDeco3 class="w-full h-auto" />
			</div>
			{#each content.elements as element, i (i)}
				{@const Shape = shapes[i % 3]}
				<div class="relative aspect-15/13 w-full max-w-90 mx-auto" class:md:mt-24={i === 1}>
					<div class="absolute inset-0" style:color={colors.blob}>
						<Shape class="w-full h-full" />
					</div>
					<div
						class="absolute inset-0 flex flex-col items-center justify-center text-center px-[16%]"
						style:transform="rotate({rotations[i % 3]}deg)"
						style:color={textColor}
					>
						<h3 class="text-body-2 font-bold">{element.title}</h3>
						<div class="prose text-label font-bold mt-3">
							{@html element.description}
						</div>
					</div>
				</div>
			{/each}
		</div>
	{/if}

	{#if content.faqs.length > 0}
		<div class="mt-12 md:mt-18 space-y-4">
			{#each content.faqs as faq, i (i)}
				<FaqQuestion id="{uid}-faq-{i}" question={faq.question} answer={faq.answer} color={colors.main} inverted={filled} />
			{/each}
		</div>
	{/if}

	{#if content.cta}
		<div class="flex justify-end mt-8">
			<CtaLink cta={content.cta} color={colors.main} inverted={filled} />
		</div>
	{/if}
</Card>
{/if}
