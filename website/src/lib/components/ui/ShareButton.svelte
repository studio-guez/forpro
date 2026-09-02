<script lang="ts">
	import IconShare from '$lib/components/svg/IconShare.svelte';
	import {
		CTA_BASE,
		ctaColorClasses,
		ctaIconSizeClasses,
		ctaSizeClasses,
		type CtaSize
	} from '$lib/utils/ctaStyles';

	interface Props {
		/** Title passed to the native share sheet. Falls back to the document title. */
		title?: string;
		label?: string;
		copiedLabel?: string;
		/** Any CSS colour: drives the outline, the label and the hover fill. */
		color?: string;
		inverted?: boolean;
		size?: CtaSize;
		class?: string;
	}

	let {
		title,
		label = 'Partager',
		copiedLabel = 'Lien copié !',
		color = 'var(--color-blue)',
		inverted = false,
		size = 'md',
		class: className = ''
	}: Props = $props();

	const colorClasses = $derived(ctaColorClasses(inverted));

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
	style:--color-cta={color}
	class="{CTA_BASE} {colorClasses} {ctaSizeClasses[size]} {className}"
	onclick={share}
>
	<span class="text-trim" aria-live="polite">{shared ? copiedLabel : label}</span>
	<IconShare class={ctaIconSizeClasses[size]} />
</button>
