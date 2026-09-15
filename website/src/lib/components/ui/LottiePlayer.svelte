<script lang="ts">
	import { onMount } from 'svelte';
	import type { CmsDocument } from '$lib/interfaces/page';

	interface Props {
		/** A `.json` or `.lottie` export, served as-is by the CMS. */
		file: CmsDocument;
		/**
		 * Text alternative, exposed as an image to assistive tech. Omit (or pass empty)
		 * for a decorative animation, which is then hidden from it.
		 */
		alt?: string | null;
		/** Box the animation is fitted into; the canvas fills it. */
		class?: string;
		/** Restart the animation when it ends. Defaults to a single play-through. */
		loop?: boolean;
	}

	let { file, alt = null, class: className = '', loop = false }: Props = $props();

	let canvas: HTMLCanvasElement | undefined = $state();

	// Loaded on mount only: it needs a canvas and a WASM runtime, and the dynamic import keeps ~200 KB off every other page.
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
				loop,
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
	<canvas
		bind:this={canvas}
		class="block w-full h-full"
		role={alt ? 'img' : undefined}
		aria-label={alt || undefined}
		aria-hidden={alt ? undefined : 'true'}
	></canvas>
</div>
