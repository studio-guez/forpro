<script lang="ts">
	import { onMount } from 'svelte';
	import type { CmsDocument, CmsImage } from '$lib/interfaces/page';
	import Img from '$lib/components/ui/Img.svelte';

	interface Props {
		/** A `.json` or `.lottie` export, served as-is by the CMS. */
		file: CmsDocument;
		/**
		 * Still image shown in place of the canvas when the animation cannot play: no
		 * WebAssembly (Safari Lockdown Mode, locked-down browsers), or the runtime or the
		 * file blocked by a network filter. Without one the box simply stays empty.
		 */
		poster?: CmsImage | null;
		/**
		 * Text alternative, exposed as an image to assistive tech. Omit (or pass empty)
		 * for a decorative animation, which is then hidden from it.
		 */
		alt?: string | null;
		/**
		 * Sizes the box. Once the file is loaded the box takes the animation's own
		 * `aspect-ratio` and exposes it as `--lottie-ratio` (width / height), so a
		 * width-only class (e.g. `w-full max-w-[calc(80vh*var(--lottie-ratio))]`) lets the
		 * animation fill the available space at its native proportions.
		 */
		class?: string;
		/** Restart the animation when it ends. Defaults to a single play-through. */
		loop?: boolean;
	}

	let { file, poster = null, alt = null, class: className = '', loop = false }: Props = $props();

	let canvas: HTMLCanvasElement | undefined = $state();
	let ratio: number | null = $state(null);
	let failed = $state(false);

	// The poster keeps the box at the animation's proportions so the surrounding layout does not jump.
	const showPoster = $derived(failed && poster !== null);

	function fail() {
		failed = true;
		if (poster) ratio = poster.width / poster.height;
	}

	// Loaded on mount only: it needs a canvas and a WASM runtime, and the dynamic import keeps ~200 KB off every other page.
	onMount(() => {
		if (!canvas) return;

		if (typeof WebAssembly === 'undefined') {
			fail();
			return;
		}

		let destroyed = false;
		let player: { destroy(): void } | null = null;

		const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

		void Promise.all([
			import('@lottiefiles/dotlottie-web'),
			// Self-hosted: the library would otherwise fetch its runtime from a CDN.
			import('@lottiefiles/dotlottie-web/dotlottie-player.wasm?url')
		])
			.then(([{ DotLottie }, { default: wasmUrl }]) => {
				if (destroyed || !canvas) return;
				DotLottie.setWasmUrl(wasmUrl);
				const dotLottie = new DotLottie({
					canvas,
					src: file.url,
					loop,
					autoplay: !reduced,
					renderConfig: { autoResize: true }
				});
				dotLottie.addEventListener('load', () => {
					const { width, height } = dotLottie.animationSize();
					if (width > 0 && height > 0) ratio = width / height;
				});
				// Fired for a failed file fetch as well as a runtime that could not be loaded from any source.
				dotLottie.addEventListener('loadError', () => {
					if (destroyed) return;
					fail();
				});
				player = dotLottie;
			})
			.catch(() => {
				if (!destroyed) fail();
			});

		return () => {
			destroyed = true;
			player?.destroy();
		};
	});
</script>

<!-- The canvas is out of flow so its default 300×150 size never dictates the box before the file is loaded. -->
<div class="relative {className}" style:aspect-ratio={ratio} style:--lottie-ratio={ratio}>
	{#if showPoster && poster}
		<Img
			image={poster}
			{alt}
			class="absolute inset-0 block w-full h-full object-contain"
			loading="eager"
		/>
	{:else}
		<canvas
			bind:this={canvas}
			class="absolute inset-0 block w-full h-full"
			role={alt ? 'img' : undefined}
			aria-label={alt || undefined}
			aria-hidden={alt ? undefined : 'true'}
		></canvas>
	{/if}
</div>
