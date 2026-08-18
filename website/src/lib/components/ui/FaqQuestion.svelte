<script lang="ts">
	import { slide } from 'svelte/transition';
	import IconPlus from '$lib/components/svg/IconPlus.svelte';
	import IconClose from '$lib/components/svg/IconClose.svelte';

	interface Props {
		id: string;
		question: string;
		answer: string;
		color?: string;
		open?: boolean;
	}

	let {
		id,
		question,
		answer,
		color = 'var(--color-teal)',
		open = $bindable(false)
	}: Props = $props();
</script>

<div style:--faq-color={color} class="border-3 border-(--faq-color) rounded-4xl overflow-hidden">
	<h3>
		<button
			type="button"
			class="w-full flex items-center justify-between gap-4 text-left px-6 md:px-9 py-4 md:py-5 text-(--faq-color)"
			aria-expanded={open}
			aria-controls="{id}-answer"
			onclick={() => (open = !open)}
		>
			<span class="text-body-2 font-bold">{question}</span>
			{#if open}
				<IconClose width={31} height={32} class="shrink-0" />
			{:else}
				<IconPlus width={31} height={32} class="shrink-0" />
			{/if}
		</button>
	</h3>
	{#if open}
		<div id="{id}-answer" role="region" aria-label={question} transition:slide={{ duration: 300 }}>
			<div class="prose px-6 md:px-9 pb-6 md:pb-8">
				{@html answer}
			</div>
		</div>
	{/if}
</div>
