<script lang="ts">
	import { tick } from 'svelte';
	import IconPlay from '$lib/components/svg/IconPlay.svelte';

	interface Props {
		src: string;
		class?: string;
	}

	let { src, class: className = '' }: Props = $props();

	let wrapper = $state<HTMLDivElement>();
	let video = $state<HTMLVideoElement>();
	let playing = $state(false);
	let near = $state(false);
	$effect(() => {
		if (!wrapper || near) return;

		const observer = new IntersectionObserver(
			(entries) => {
				if (!entries.some((entry) => entry.isIntersecting)) return;
				near = true;
				observer.disconnect();
			},
			{ rootMargin: '200px' }
		);

		observer.observe(wrapper);
		return () => observer.disconnect();
	});
	const videoSrc = $derived(near ? `${src}#t=0.1` : undefined);

	const playVideo = async () => {
		if (!video) return;
		near = true;
		playing = true;
		await tick();
		video.currentTime = 0;
		video.play();
		video.focus();
	};
</script>

<div bind:this={wrapper} class="relative w-full h-full">
	<!-- svelte-ignore a11y_media_has_caption -->
	<video
		bind:this={video}
		src={videoSrc}
		preload="metadata"
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
