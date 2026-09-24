<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import type { BannerAnnouncement } from '$lib/interfaces/global';
	import IconPause from '$lib/components/svg/IconPause.svelte';
	import IconPlaySmall from '$lib/components/svg/IconPlaySmall.svelte';

	interface Props {
		announcements: BannerAnnouncement[];
	}

	let { announcements }: Props = $props();

	// WCAG 2.2.2: auto-scrolling longer than 5s needs a pause control that is not hover-only.
	let paused = $state(false);

	let copyWidth = $state(0);
	let viewportWidth = $state(0);

	// The track must stay wider than the viewport once a full copy has scrolled out, or a gap appears before the loop restarts.
	const copies = $derived(
		copyWidth > 0 ? Math.max(Math.ceil(viewportWidth / copyWidth) + 1, 2) : 2
	);
	// translateX percentages resolve against the track's own width, so one copy is 100 / copies.
	const shift = $derived(100 / copies);
	const duration = $derived(Math.max(copyWidth / 60, 10));

	// Not animated until the first copy is measured: the provisional values would show a burst of speed and a jump.
	const measured = $derived(copyWidth > 0 && viewportWidth > 0);

	// `animate-marquee` sets the `animation` shorthand, emitted after the utilities, so an equal-specificity pause class always loses to it.
	// Hence the button state on an inline style and the hover pause on an `!important` utility.
</script>

<svelte:window bind:innerWidth={viewportWidth} />

{#snippet content(announcement: BannerAnnouncement)}
	<span class="text-body-2">{announcement.title}</span>
	{#if announcement.description}
		<span class="text-body-1">{announcement.description}</span>
	{/if}
{/snippet}

{#snippet items(duplicate: boolean)}
	{#each announcements as announcement, index (index)}
		<li class="shrink-0 px-6">
			{#if announcement.url}
				<a
					href={announcement.url}
					target={announcement.target ?? undefined}
					rel={announcement.target === '_blank' ? 'noopener noreferrer' : undefined}
					tabindex={duplicate ? -1 : undefined}
					class="flex items-baseline gap-x-3 underline decoration-transparent hover:decoration-current transition-colors"
				>
					{@render content(announcement)}
				</a>
			{:else}
				<span class="flex items-baseline gap-x-3">
					{@render content(announcement)}
				</span>
			{/if}
		</li>
	{/each}
{/snippet}
<aside
	aria-label="Annonces"
	class="sticky bottom-0 z-20 h-12 bg-green text-black overflow-hidden group"
>
	<button
		type="button"
		onclick={() => (paused = !paused)}
		aria-pressed={paused}
		aria-label={paused
			? 'Reprendre le défilement des annonces'
			: 'Mettre en pause le défilement des annonces'}
		class="absolute right-1 top-1/2 z-10 -translate-y-1/2 shrink-0 rounded-full bg-black p-1 text-green transition-opacity group-hover:opacity-100 focus-visible:opacity-100 [@media(hover:none)]:opacity-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black {paused
			? ''
			: 'opacity-0'}"
	>
		{#if paused}
			<IconPlaySmall class="w-5 h-5" />
		{:else}
			<IconPause class="w-5 h-5" />
		{/if}
	</button>

	<div
		class="flex h-full items-center w-max hover:[animation-play-state:paused]! {measured
			? 'animate-marquee'
			: ''}"
		style="animation-duration: {duration}s; --marquee-shift: {shift}%; animation-play-state: {paused
			? 'paused'
			: 'running'}"
	>
		<ul bind:clientWidth={copyWidth} class="flex items-center">
			{@render items(false)}
		</ul>
		{#each { length: copies - 1 }, index (index)}
			<ul aria-hidden="true" class="flex items-center">
				{@render items(true)}
			</ul>
		{/each}
	</div>
</aside>
