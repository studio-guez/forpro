<script lang="ts">
	import { slide } from 'svelte/transition';
	import IconPlus from '$lib/components/svg/IconPlus.svelte';
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import ShareButton from '$lib/components/ui/ShareButton.svelte';

	interface Props {
		id: string;
		question: string;
		answer: string;
		color?: string;
		inverted?: boolean;
		open?: boolean;
		/**
		 * Link the answer offers to share, resolved against the current page — so
		 * `?question=slug` shares the list opened on this question. No link, no
		 * share button: a question listed inside a block is not addressable.
		 */
		shareUrl?: string | null;
	}

	let {
		id,
		question,
		answer,
		color = 'var(--color-teal)',
		inverted = false,
		open = $bindable(false),
		shareUrl = null
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
	{id}
	style:--faq-color={color}
	class="border-3 lg:border-4 {borderClass} rounded-[1.3125rem] lg:rounded-[2.125rem] overflow-hidden {className} transition-colors"
>
	<h3>
		<button
			type="button"
			class="w-full flex items-center justify-between gap-2 lg:gap-3 text-left px-2.25 lg:px-7.5 py-1.5 lg:py-3.5 min-h-9 lg:min-h-15"
			aria-expanded={open}
			aria-controls={open ? `${id}-answer` : undefined}
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
			<div class="px-2.25 lg:px-7.5 pb-6 lg:pb-8">
				<div class="prose">
					{@html answer}
				</div>

				{#if shareUrl}
					<!-- The open panel takes the faq colour, so the pill is drawn the other
					     way round from the collapsed box it sits in. -->
					<div class="mt-6 lg:mt-8 flex justify-end">
						<ShareButton url={shareUrl} title={question} {color} inverted={!inverted} />
					</div>
				{/if}
			</div>
		</div>
	{/if}
</div>
