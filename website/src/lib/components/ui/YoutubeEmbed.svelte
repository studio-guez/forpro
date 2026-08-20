<script lang="ts">
	import type { YoutubeEmbed } from '$lib/interfaces/eventProject';

	interface Props {
		embed: YoutubeEmbed;
		title?: string;
		class?: string;
	}

	let { embed, title = 'Vidéo YouTube', class: className = '' }: Props = $props();

	// Shorts are vertical (9:16); regular videos keep the standard 16:9 ratio.
	const aspect = $derived(embed.type === 'short' ? 'aspect-[9/16]' : 'aspect-video');
</script>

<div class={['relative w-full overflow-hidden', aspect, className]}>
	<iframe
		src={embed.embedUrl}
		{title}
		loading="lazy"
		referrerpolicy="strict-origin-when-cross-origin"
		allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
		allowfullscreen
		class="absolute inset-0 w-full h-full"
	></iframe>
</div>
