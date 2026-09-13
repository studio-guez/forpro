<script lang="ts">
	import type { HomeWelcomeCard } from '$lib/interfaces/home';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import { reveal } from '$lib/utils/reveal';

	interface Props {
		/** The 3 cards, in order; each slot has its own fixed colours and tilt. */
		cards: HomeWelcomeCard[];
		class?: string;
	}

	let { cards, class: className = '' }: Props = $props();

	// Design constants per slot: colours, which side the card sits on (and enters from) and
	// its resting tilt. Cards alternate sides so each one overlaps the previous corner.
	const slots = [
		{
			background: 'var(--color-green)',
			color: 'var(--color-blue)',
			side: 'left',
			tilt: -3,
			inverted: false
		},
		{
			background: 'var(--color-blue)',
			color: 'var(--color-white)',
			side: 'right',
			tilt: 2,
			inverted: true
		},
		{
			background: 'var(--color-pink)',
			color: 'var(--color-white)',
			side: 'left',
			tilt: -3,
			inverted: true
		}
	] as const;
</script>

<div use:reveal class={['stage flex flex-col', className]}>
	{#each cards as card, i (i)}
		{@const slot = slots[i % slots.length]}
		<div
			class={[
				'card relative rounded-3xl p-6 lg:p-12 lg:w-[62%]',
				slot.side === 'left' ? 'from-left self-start' : 'from-right self-end',
				i > 0 && '-mt-6 lg:-mt-20'
			]}
			style:--tilt="{slot.tilt}deg"
			style:z-index={i + 1}
			style:background-color={slot.background}
			style:color={slot.color}
		>
			<h2 class="text-h1">{card.title}</h2>
			{#if card.cta}
				<div class={['mt-6 lg:mt-9 flex', slot.side === 'left' ? 'justify-start' : 'justify-end']}>
					<!-- Inverted pills turn to the card colour on hover, so that is the colour they take. -->
					<CtaLink
						cta={card.cta}
						color={slot.inverted ? slot.background : slot.color}
						inverted={slot.inverted}
					/>
				</div>
			{/if}
		</div>
	{/each}
</div>

<style>
	.card {
		rotate: var(--tilt);
	}

	/* The entrance: pushed off to its side and over-rotated while the stage waits, then
	   slid and turned back onto its resting tilt — clockwise from the left, counter-clockwise
	   from the right. `reveal` only ever sets `pending` on the client, so reduced motion,
	   no-JS and the server render all get the resting state above. The attribute is set
	   by the action, not the markup, hence `:global` — Svelte would prune the rule otherwise. */
	@media (prefers-reduced-motion: no-preference) {
		.card {
			transition:
				translate 0.8s cubic-bezier(0.22, 1, 0.36, 1),
				rotate 0.8s cubic-bezier(0.22, 1, 0.36, 1);
		}
		.stage:global([data-reveal='pending']) .from-left {
			translate: -40% 0;
			rotate: calc(var(--tilt) - 10deg);
		}
		.stage:global([data-reveal='pending']) .from-right {
			translate: 40% 0;
			rotate: calc(var(--tilt) + 10deg);
		}
	}
</style>
