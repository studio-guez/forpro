<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- the href comes from the CMS */
	import { onMount } from 'svelte';
	import { cookieConsent } from '$lib/utils/cookieConsent.svelte';
	import { CTA_BASE, CTA_LABEL, ctaColorClasses, ctaSizeClasses } from '$lib/utils/ctaStyles';
	import ShapeCookies from '$lib/components/svg/ShapeCookies.svelte';

	interface Props {
		/** Intro copy, from the Panel's Cookies tab. */
		text: string | null;
		/** Picked in the Panel (Cookies tab); null hides the link. */
		privacyPolicyUrl: string | null;
		/** Lifts the card above the announcements marquee, which is sticky at the bottom. */
		raised?: boolean;
	}

	let { text, privacyPolicyUrl, raised = false }: Props = $props();

	let acceptButton = $state<HTMLButtonElement | null>(null);

	onMount(cookieConsent.load);

	const visible = $derived(
		cookieConsent.loaded && (!cookieConsent.decided || cookieConsent.reopened)
	);

	// Reopened from the footer: move focus into the banner rather than leaving it on the footer link.
	$effect(() => {
		if (cookieConsent.reopened) acceptButton?.focus();
	});

	const RULE = 'h-px shrink-0 bg-white';
	const BUTTON = `${CTA_BASE} ${ctaSizeClasses.md} justify-center`;
</script>

{#if visible}
	<aside
		aria-labelledby="cookie-banner-title"
		class="fixed left-5 z-30 w-[min(36rem,calc(100vw-2.5rem))] overflow-hidden rounded-3xl bg-pink p-6 text-white lg:left-12 {raised
			? 'bottom-17'
			: 'bottom-5'}"
	>
		<ShapeCookies class="pointer-events-none absolute right-0 bottom-0 text-green" />

		<div class="relative flex flex-col gap-4">
			<h2 id="cookie-banner-title">Ce site utilise des cookies ! 🍪</h2>
			<div class={RULE}></div>

			{#if text}
				<p class="text-body-2 whitespace-pre-line">{text}</p>
			{/if}
			<div class={RULE}></div>
			<div class="flex flex-wrap justify-end gap-3">
				<button
					type="button"
					onclick={cookieConsent.refuse}
					style:--color-cta="var(--color-pink)"
					class="{BUTTON} {ctaColorClasses(true)}"
				>
					<span class={CTA_LABEL}>Refuser</span>
				</button>
				<button
					type="button"
					bind:this={acceptButton}
					onclick={cookieConsent.accept}
					class="{BUTTON} border-white bg-white text-pink hover:bg-transparent hover:text-white"
				>
					<span class={CTA_LABEL}>Accepter</span>
				</button>
			</div>

			{#if privacyPolicyUrl}
				<a
					href={privacyPolicyUrl}
					class="text-label self-start underline underline-offset-4 opacity-80 transition-opacity hover:opacity-100"
				>
					Politique de confidentialité
				</a>
			{/if}
		</div>
	</aside>
{/if}
