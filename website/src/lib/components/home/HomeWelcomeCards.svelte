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

<div class={['flex flex-col', className]}>
	{#each cards as card, i (i)}
		{@const slot = slots[i % slots.length]}
		<!-- Each card is its own reveal target, so it enters when *it* scrolls into view, not
			 when the group does. The action sits on an untransformed slot wrapper: the card
			 itself is parked off-screen while pending, and an element that far outside the
			 viewport would never intersect it. -->
		<div
			use:reveal={{ trigger: 0.7 }}
			class={[
				'slot relative lg:w-[70%]',
				slot.side === 'left' ? 'self-start' : 'self-end',
				i > 0 && '-mt-6 lg:-mt-20'
			]}
			style:z-index={i + 1}
		>
			<div
				class={['card rounded-3xl p-6 lg:p-8', slot.side === 'left' ? 'from-left' : 'from-right']}
				style:--tilt="{slot.tilt}deg"
				style:background-color={slot.background}
				style:color={slot.color}
			>
				<h2 class="text-h0">{card.title}</h2>
				{#if card.cta}
					<div
						class={['mt-6 lg:mt-9 flex', slot.side === 'left' ? 'justify-start' : 'justify-end']}
					>
						<!-- Inverted pills turn to the card colour on hover, so that is the colour they take. -->
						<CtaLink
							cta={card.cta}
							color={slot.inverted ? slot.background : slot.color}
							inverted={slot.inverted}
						/>
					</div>
				{/if}
			</div>
		</div>
	{/each}
</div>

<style>
	.card {
		rotate: var(--tilt);
	}

	/* The entrance: parked past the edge of the screen on its side and over-rotated while
	   the card waits, then slid and turned back onto its resting tilt — clockwise from the
	   left, counter-clockwise from the right. The offset is the card's own width (its inner
	   edge reaches the container edge) plus a slice of the viewport that covers the page
	   gutter and the corners the tilt pushes out, so nothing peeks in before it starts.
	   `reveal` only ever sets `pending` on the client, so reduced motion, no-JS and the
	   server render all get the resting state above. The attribute is set by the action on
	   the wrapper, not the markup, hence `:global` — Svelte would prune the rule otherwise. */
	@media (prefers-reduced-motion: no-preference) {
		/* Only the way in is animated: the jump *to* the parked position happens at hydration,
		   after the server render has painted the card in place, and must be instant. */
		.slot:global([data-reveal='done']) .card {
			transition:
				translate 0.8s cubic-bezier(0.22, 1, 0.36, 1),
				rotate 0.8s cubic-bezier(0.22, 1, 0.36, 1),
				/* Invisible while parked, and solid again well before it lands. */ opacity 0.15s ease-out;
		}
		.slot:global([data-reveal='pending']) .card {
			opacity: 0;
		}
		.slot:global([data-reveal='pending']) .from-left {
			translate: calc(-100% - 10vw) 0;
			rotate: calc(var(--tilt) - 15deg);
		}
		.slot:global([data-reveal='pending']) .from-right {
			translate: calc(100% + 10vw) 0;
			rotate: calc(var(--tilt) + 15deg);
		}
	}
</style>
