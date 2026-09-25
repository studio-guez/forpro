<script lang="ts">
	import IconShare from '$lib/components/svg/IconShare.svelte';
	import {
		CTA_BASE,
		CTA_LABEL,
		ctaColorClasses,
		ctaIconSizeClasses,
		ctaSizeClasses,
		type CtaSize
	} from '$lib/utils/ctaStyles';

	interface Props {
		/** Title passed to the native share sheet. Falls back to the document title. */
		title?: string;
		/**
		 * Link to share, resolved against the current page — so `?question=slug`
		 * shares this page opened on that question. Defaults to the current URL.
		 */
		url?: string;
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
		url,
		label = 'Partager',
		copiedLabel = 'Lien copié !',
		color = 'var(--color-blue)',
		inverted = false,
		size = 'md',
		class: className = ''
	}: Props = $props();

	const colorClasses = $derived(ctaColorClasses(inverted));

	let shared = $state(false);

	const share = async (): Promise<void> => {
		const shareUrl = url ? new URL(url, window.location.href).href : window.location.href;
		try {
			if (navigator.share) {
				await navigator.share({ title: title ?? document.title, url: shareUrl });
				return;
			}
			await navigator.clipboard.writeText(shareUrl);
			shared = true;
			setTimeout(() => (shared = false), 3000);
		} catch {}
	};
</script>

<button
	type="button"
	style:--color-cta={color}
	class="{CTA_BASE} {colorClasses} {ctaSizeClasses[size]} {className}"
	onclick={share}
>
	<span class={CTA_LABEL} aria-live="polite">{shared ? copiedLabel : label}</span>
	<IconShare class={ctaIconSizeClasses[size]} />
</button>
