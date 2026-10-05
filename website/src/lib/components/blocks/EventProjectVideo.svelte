<script lang="ts">
	import Video from '$lib/components/ui/Video.svelte';
	import type { VideoItem } from '$lib/interfaces/page';

	interface Props {
		video: VideoItem | null;
		title: string;
		class?: string;
	}

	let { video, title, class: className = '' }: Props = $props();
</script>

{#if video}
	<section class={['px-base', className]} aria-label="Vidéo — {title}">
		<Video {video} title="Vidéo — {title}" />
	</section>
{/if}
