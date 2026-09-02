<script lang="ts">
	import IconLink from '$lib/components/svg/IconLink.svelte';

	interface Props {
		/** Title passed to the native share sheet. Falls back to the document title. */
		title?: string;
		label?: string;
		copiedLabel?: string;
		/** Any CSS colour: drives the border, the label and the hover fill. */
		color?: string;
		class?: string;
	}

	let {
		title,
		label = 'Partager',
		copiedLabel = 'Lien copié !',
		color = 'var(--color-blue)',
		class: className = ''
	}: Props = $props();

	let shared = $state(false);

	// Native share sheet when available (mobile), clipboard fallback otherwise.
	const share = async (): Promise<void> => {
		const url = window.location.href;
		try {
			if (navigator.share) {
				await navigator.share({ title: title ?? document.title, url });
				return;
			}
			await navigator.clipboard.writeText(url);
			shared = true;
			setTimeout(() => (shared = false), 3000);
		} catch {
			// The user dismissed the share sheet, or the clipboard is unavailable.
		}
	};
</script>

<button
	type="button"
	style:--share-color={color}
	class={[
		'share-button text-label inline-flex items-center gap-2.5 rounded-full border-3 px-5.5 py-3 leading-none transition-colors',
		className
	]}
	onclick={share}
>
	<span aria-live="polite">{shared ? copiedLabel : label}</span>
	<IconLink class="w-5.5 h-5.5" />
</button>

<style>
	/* The colour is a prop, so it cannot be a Tailwind class. */
	.share-button {
		border-color: var(--share-color);
		color: var(--share-color);
	}

	.share-button:hover {
		background-color: var(--share-color);
		color: var(--color-white);
	}
</style>
