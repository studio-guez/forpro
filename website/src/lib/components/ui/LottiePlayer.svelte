<script lang="ts">
	import { onMount } from 'svelte';
	import type { DotLottie } from '@lottiefiles/dotlottie-web';
	import type { CmsDocument, CmsImage } from '$lib/interfaces/page';
	import Img from '$lib/components/ui/Img.svelte';
	import { contrast } from '$lib/utils/contrast.svelte';

	interface Props {
		/** A `.json` or `.lottie` export, served as-is by the CMS, with its optional high-contrast replacement. */
		file: CmsDocument & { readonly highContrast?: CmsDocument | null };
		/**
		 * Still image shown in place of the canvas when the animation cannot play: no
		 * WebAssembly (Safari Lockdown Mode, locked-down browsers), or the runtime or the
		 * file blocked by a network filter. Without one, a failure leaves the box empty.
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
	let posterWanted = $state(false);
	let player: DotLottie | null = $state.raw(null);

	const src = $derived(contrast.active && file.highContrast ? file.highContrast.url : file.url);

	const ZIP_SIGNATURE = [0x50, 0x4b, 0x03, 0x04];

	// Decodes like the library's own fetch: a `.lottie` is a zip archive, anything else is Lottie JSON.
	async function fetchAnimation(url: string, signal: AbortSignal): Promise<string | ArrayBuffer> {
		const response = await fetch(url, { signal });
		if (!response.ok) throw new Error(`${response.status} ${response.statusText}: ${url}`);
		const data = await response.arrayBuffer();
		const head = new Uint8Array(data.slice(0, ZIP_SIGNATURE.length));
		return ZIP_SIGNATURE.every((byte, i) => head[i] === byte)
			? data
			: new TextDecoder().decode(data);
	}

	// Follows the high-contrast toggle without remounting: `load()` swaps the animation on the same canvas.
	// The file is fetched here rather than through `load({ src })`, which never cancels an earlier fetch:
	// aborting on change keeps an older, slower file from landing after the newer one.
	$effect(() => {
		const target = player;
		if (!target) return;
		const current = src;
		const controller = new AbortController();
		void fetchAnimation(current, controller.signal)
			.then((data) => {
				if (!controller.signal.aborted) target.load({ data, loop, autoplay: true });
			})
			.catch(() => {
				if (!controller.signal.aborted) usePoster();
			});
		return () => controller.abort();
	});

	// The poster keeps the box at the animation's proportions so the surrounding layout does not jump.
	const showPoster = $derived(posterWanted && poster !== null);

	function usePoster() {
		posterWanted = true;
		if (poster) ratio = poster.width / poster.height;
	}

	// Loaded on mount only: it needs a canvas and a WASM runtime, and the dynamic import keeps ~200 KB off every other page.
	onMount(() => {
		if (!canvas) return;

		if (typeof WebAssembly === 'undefined') {
			usePoster();
			return;
		}

		// Children mount before the layout that normally initialises it, and the first `src` must already follow the mode.
		contrast.init();

		let destroyed = false;
		let instance: DotLottie | null = null;

		void Promise.all([
			import('@lottiefiles/dotlottie-web'),
			// Self-hosted: the library would otherwise fetch its runtime from a CDN.
			import('@lottiefiles/dotlottie-web/dotlottie-player.wasm?url')
		])
			.then(([{ DotLottie }, { default: wasmUrl }]) => {
				if (destroyed || !canvas) return;
				DotLottie.setWasmUrl(wasmUrl);
				// No `src`: the effect above fetches every file, the first one included.
				const dotLottie = new DotLottie({ canvas, renderConfig: { autoResize: true } });
				instance = dotLottie;
				// `load()` is a no-op until the WASM core exists, so the effect only loads files once it does.
				dotLottie.addEventListener('ready', () => (player = dotLottie));
				dotLottie.addEventListener('load', () => {
					const { width, height } = dotLottie.animationSize();
					if (width > 0 && height > 0) ratio = width / height;
				});
				// Fired for a failed file fetch as well as a runtime that could not be loaded from any source.
				dotLottie.addEventListener('loadError', () => {
					if (destroyed) return;
					usePoster();
				});
			})
			.catch(() => {
				if (!destroyed) usePoster();
			});

		return () => {
			destroyed = true;
			instance?.destroy();
			player = null;
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
