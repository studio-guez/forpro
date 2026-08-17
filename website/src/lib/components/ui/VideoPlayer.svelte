<script lang="ts">
	import IconPlay from '$lib/components/svg/IconPlay.svelte';

	interface Props {
		src: string;
		class?: string;
	}

	let { src, class: className = '' }: Props = $props();

	const playVideo = (event: MouseEvent) => {
		const button = event.currentTarget as HTMLElement;
		const video = button.previousElementSibling as HTMLVideoElement | null;
		if (!video) return;
		video.controls = true;
		video.play();
		button.hidden = true;
	};
</script>

<div class="relative w-full h-full">
	<!-- svelte-ignore a11y_media_has_caption -->
	<video
		{src}
		playsinline
		controlslist="nodownload"
		class={['w-full h-full object-cover aspect-video', className]}
	></video>
	<button
		type="button"
		onclick={playVideo}
		aria-label="Lire la vidéo"
		class="absolute inset-0 grid place-items-center cursor-pointer text-white opacity-80"
	>
		<IconPlay class="w-24 h-auto" />
	</button>
</div>
