<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import type { BannerAnnouncement } from '$lib/interfaces/global';

	interface Props {
		announcements: BannerAnnouncement[];
	}

	let { announcements }: Props = $props();

	let bannerHeight = $state(0);
	let copyWidth = $state(0);
	let viewportWidth = $state(0);

	// The banner is fixed, so the page has to reserve its height or it would cover the footer.
	$effect(() => {
		document.body.style.paddingBottom = `${bannerHeight}px`;
		return () => {
			document.body.style.paddingBottom = '';
		};
	});

	// Short content needs more than two copies: the track must stay wider than the viewport
	// even once a full copy has scrolled out, otherwise a gap appears before the loop restarts.
	const copies = $derived(
		copyWidth > 0 ? Math.max(Math.ceil(viewportWidth / copyWidth) + 1, 2) : 2
	);
	// translateX percentages resolve against the track's own width, so one copy is 100 / copies.
	const shift = $derived(100 / copies);
	// One copy scrolls past at ~60px/s, whatever the amount of text.
	const duration = $derived(Math.max(copyWidth / 60, 10));
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
		<li class="shrink-0 px-6 py-2.5">
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
	bind:clientHeight={bannerHeight}
	aria-label="Annonces"
	class="fixed bottom-0 inset-x-0 z-20 bg-green text-black overflow-hidden"
>
	<div
		class="flex w-max motion-safe:animate-marquee hover:[animation-play-state:paused]"
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
