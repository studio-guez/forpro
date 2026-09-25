<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import type { BannerAnnouncement } from '$lib/interfaces/global';
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import { dismissBanner } from '$lib/utils/bannerDismissal';

	interface Props {
		announcements: BannerAnnouncement[];
		onclose: () => void;
	}

	let { announcements, onclose }: Props = $props();

	// WCAG 2.2.2: auto-scrolling longer than 5s needs a pause, stop or hide control that is not hover-only.
	const close = () => {
		dismissBanner();
		onclose();
	};

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
	// Hence the hover pause on an `!important` utility.
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
	class="group sticky bottom-0 z-20 h-12 bg-green text-black overflow-hidden"
>
	<button
		type="button"
		onclick={close}
		aria-label="Fermer les annonces"
		class="absolute right-1 top-1/2 z-10 -translate-y-1/2 shrink-0 rounded-full bg-black p-1 text-green focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black transition-opacity [@media(hover:hover)]:opacity-0 group-hover:opacity-100 group-focus-within:opacity-100"
	>
		<IconClose class="w-5 h-5" />
	</button>

	<div
		class="flex h-full items-center w-max hover:[animation-play-state:paused]! {measured
			? 'animate-marquee'
			: ''}"
		style="animation-duration: {duration}s; --marquee-shift: {shift}%"
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
