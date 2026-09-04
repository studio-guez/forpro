<script lang="ts">
	import { tick } from 'svelte';
	import IconPlay from '$lib/components/svg/IconPlay.svelte';

	interface Props {
		src: string;
		class?: string;
	}

	let { src, class: className = '' }: Props = $props();

	let video = $state<HTMLVideoElement>();
	let playing = $state(false);

	const playVideo = async () => {
		if (!video) return;
		playing = true;
		video.play();
		await tick();
		video.focus();
	};
</script>

<div class="relative w-full h-full">
	<!-- svelte-ignore a11y_media_has_caption -->
	<video
		bind:this={video}
		{src}
		playsinline
		controls={playing}
		controlslist="nodownload"
		class={['w-full h-full object-cover aspect-video', className]}
	></video>
	{#if !playing}
		<button
			type="button"
			onclick={playVideo}
			aria-label="Lire la vidéo"
			class="absolute inset-0 grid place-items-center cursor-pointer text-white opacity-80"
		>
			<IconPlay class="w-24 h-auto" />
		</button>
	{/if}
</div>
