<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import type { BannerAnnouncement } from '$lib/interfaces/global';

	interface Props {
		announcements: BannerAnnouncement[];
	}

	let { announcements }: Props = $props();

	let bannerHeight = $state(0);
	let trackWidth = $state(0);

	// The banner is fixed, so the page has to reserve its height or it would cover the footer.
	$effect(() => {
		document.body.style.paddingBottom = `${bannerHeight}px`;
		return () => {
			document.body.style.paddingBottom = '';
		};
	});

	// One copy of the track scrolls past at ~60px/s, whatever the amount of text.
	const duration = $derived(Math.max(trackWidth / 60, 10));
</script>

{#snippet items()}
	{#each announcements as announcement, index (index)}
		<li class="shrink-0 flex items-baseline gap-x-2 px-6 py-2">
			<span class="text-label font-bold">{announcement.title}</span>
			{#if announcement.description}
				<span class="text-label">{announcement.description}</span>
			{/if}
			{#if announcement.url}
				<a
					href={announcement.url}
					target={announcement.target ?? undefined}
					rel={announcement.target === '_blank' ? 'noopener noreferrer' : undefined}
					class="text-label underline decoration-transparent hover:decoration-current transition-colors"
				>
					En savoir plus
				</a>
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
		style="animation-duration: {duration}s"
	>
		<ul bind:clientWidth={trackWidth} class="flex items-center">
			{@render items()}
		</ul>
		<!-- Second copy makes the -50% translation loop seamlessly -->
		<ul aria-hidden="true" class="flex items-center">
			{@render items()}
		</ul>
	</div>
</aside>
