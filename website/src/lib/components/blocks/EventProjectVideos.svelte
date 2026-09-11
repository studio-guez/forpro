<script lang="ts">
	import Video from '$lib/components/ui/Video.svelte';
	import type { VideoItem } from '$lib/interfaces/page';

	interface Props {
		videos?: VideoItem[];
		title: string;
		class?: string;
	}

	let { videos = [], title, class: className = '' }: Props = $props();
</script>

{#if videos.length > 0}
	<section class={['px-base', className]} aria-label="Vidéos — {title}">
		<div class="flex flex-col gap-6">
			{#each videos as video, index (index)}
				<Video {video} title="Vidéo — {title}" />
			{/each}
		</div>
	</section>
{/if}
