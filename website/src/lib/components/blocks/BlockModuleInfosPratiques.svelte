<script lang="ts">
	import type { ModuleInfosPratiquesContent } from '$lib/interfaces/page';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import FaqQuestion from '$lib/components/ui/FaqQuestion.svelte';
	import ThreeElements from '$lib/components/ui/ThreeElements.svelte';

	interface Props {
		content: ModuleInfosPratiquesContent;
	}

	let { content }: Props = $props();

	// The block is always teal; the default variant is filled (teal bg), inverted is light.
	const colors = { main: 'var(--color-teal)', blob: 'var(--color-teal-light)' };

	const filled = $derived(content.variant !== 'inverted');
	const textColor = $derived(filled ? 'var(--color-white)' : colors.main);
	const decoColor = $derived(filled ? colors.blob : colors.main);

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
		<ThreeElements
			elements={content.elements}
			blobColor={colors.blob}
			{textColor}
			{decoColor}
			class="mt-12 md:mt-6"
		/>
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
