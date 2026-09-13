<script lang="ts">
	import { onMount } from 'svelte';
	import type { CmsDocument } from '$lib/interfaces/page';

	interface Props {
		/** A `.json` or `.lottie` export, served as-is by the CMS. */
		file: CmsDocument;
		/** Box the animation is fitted into; the canvas fills it. */
		class?: string;
	}

	let { file, class: className = '' }: Props = $props();

	let canvas: HTMLCanvasElement | undefined = $state();

	// The player is loaded on mount only: it needs a canvas and a WASM runtime, neither of
	// which exists during SSR, and a dynamic import keeps the ~200 KB out of every other page.
	onMount(() => {
		if (!canvas) return;

		let destroyed = false;
		let player: { destroy(): void } | null = null;

		const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		void Promise.all([
			import('@lottiefiles/dotlottie-web'),
			// Self-hosted: the library would otherwise fetch its runtime from a CDN.
			import('@lottiefiles/dotlottie-web/dotlottie-player.wasm?url')
		]).then(([{ DotLottie }, { default: wasmUrl }]) => {
			if (destroyed || !canvas) return;
			DotLottie.setWasmUrl(wasmUrl);
			player = new DotLottie({
				canvas,
				src: file.url,
				loop: true,
				// Reduced motion still gets the artwork: the first frame, standing still.
				autoplay: !reduced,
				renderConfig: { autoResize: true }
			});
		});

		return () => {
			destroyed = true;
			player?.destroy();
		};
	});
</script>

<div class={className}>
	<canvas bind:this={canvas} class="block w-full h-full" aria-hidden="true"></canvas>
</div>
