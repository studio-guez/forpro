<script lang="ts">
	import type { VideoItem } from '$lib/interfaces/page';
	import VideoPlayer from '$lib/components/ui/VideoPlayer.svelte';
	import YoutubeEmbed from '$lib/components/ui/YoutubeEmbed.svelte';

	// One item of the shared CMS `fields/video` structure: an uploaded file
	// (self-hosted player) or a YouTube embed (iframe).
	interface Props {
		video: VideoItem;
		title?: string;
		class?: string;
	}

	let { video, title = 'Vidéo', class: className = '' }: Props = $props();

	// Shorts are vertical: keep them narrow and centered instead of stretching them to the column.
	const isShort = $derived(video.source === 'youtube' && video.embed.type === 'short');
</script>

<div class={[isShort ? 'mx-auto w-full max-w-xs' : '', className]}>
	{#if video.source === 'youtube'}
		<YoutubeEmbed embed={video.embed} {title} class="rounded-2xl" />
	{:else}
		<div class="overflow-hidden rounded-2xl">
			<VideoPlayer src={video.file.url} />
		</div>
	{/if}
</div>
