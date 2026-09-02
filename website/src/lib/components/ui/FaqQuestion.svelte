<script lang="ts">
	import { slide } from 'svelte/transition';
	import IconPlus from '$lib/components/svg/IconPlus.svelte';
	import IconClose from '$lib/components/svg/IconClose.svelte';

	interface Props {
		id: string;
		question: string;
		answer: string;
		color?: string;
		inverted?: boolean;
		open?: boolean;
	}

	let {
		id,
		question,
		answer,
		color = 'var(--color-teal)',
		inverted = false,
		open = $bindable(false)
	}: Props = $props();

	const borderClass = $derived(inverted ? 'border-white' : 'border-(--faq-color)');
	const className = $derived(
		inverted
			? open
				? 'bg-white text-(--faq-color)'
				: 'text-white hover:bg-white hover:text-(--faq-color)'
			: open
				? 'bg-(--faq-color) text-white'
				: 'text-(--faq-color) hover:bg-(--faq-color) hover:text-white'
	);
</script>

<div
	style:--faq-color={color}
	class="border-3 lg:border-4 {borderClass} rounded-[1.3125rem] lg:rounded-[2.125rem] overflow-hidden {className} transition-colors"
>
	<h3>
		<button
			type="button"
			class="w-full flex items-center justify-between gap-2 lg:gap-3 text-left px-2.25 lg:px-7.5 h-9 lg:h-15"
			aria-expanded={open}
			aria-controls="{id}-answer"
			onclick={() => (open = !open)}
		>
			<span class="text-base lg:text-2xl font-bold">{question}</span>
			{#if open}
				<IconClose class="shrink-0 w-6 lg:w-9.5 h-6 lg:h-9.5" />
			{:else}
				<IconPlus class="shrink-0 w-6 lg:w-9.5 h-6 lg:h-9.5" />
			{/if}
		</button>
	</h3>
	{#if open}
		<div id="{id}-answer" role="region" aria-label={question} transition:slide={{ duration: 300 }}>
			<div class="prose px-2.25 lg:px-7.5 pb-6 lg:pb-8">
				{@html answer}
			</div>
		</div>
	{/if}
</div>
