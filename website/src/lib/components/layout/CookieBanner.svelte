<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- the href comes from the CMS */
	import { onMount, tick } from 'svelte';
	import { cookieConsent, type CookieChoice } from '$lib/utils/cookieConsent.svelte';
	import { CTA_BASE, ctaSizeClasses } from '$lib/utils/ctaStyles';
	import ShapeCookies from '$lib/components/svg/ShapeCookies.svelte';
	import IconCheck from '$lib/components/svg/IconCheck.svelte';

	interface Props {
		/** Intro copy, from the Panel's Cookies tab. */
		text: string | null;
		/** Picked in the Panel (Cookies tab); null hides the link. */
		privacyPolicyUrl: string | null;
		/** Lifts the card above the announcements marquee, which is sticky at the bottom. */
		raised?: boolean;
	}

	let { text, privacyPolicyUrl, raised = false }: Props = $props();

	let view = $state<'intro' | 'preferences'>('intro');
	// Draft choice: only written to storage when "Enregistrer mes préférences" is pressed.
	let choice = $state<CookieChoice>({ performance: false, marketing: false });

	let saveButton = $state<HTMLButtonElement | null>(null);

	onMount(cookieConsent.load);

	const visible = $derived(cookieConsent.loaded && !cookieConsent.decided);

	async function openPreferences() {
		view = 'preferences';
		// The button that had focus is gone with the intro view; without this the focus ring
		// falls back to <body> and keyboard users lose their place.
		await tick();
		saveButton?.focus();
	}

	const categories: { key: keyof CookieChoice | 'necessary'; label: string }[] = [
		{ key: 'necessary', label: 'Cookies Nécessaires' },
		{ key: 'performance', label: 'Cookies de Performance' },
		{ key: 'marketing', label: 'Cookies de Marketing' }
	];

	const RULE = 'h-px shrink-0 bg-white';
	const BUTTON = `${CTA_BASE} ${ctaSizeClasses.md} justify-center border-white`;
</script>

{#snippet toggle(label: string, checked: boolean, onchange: ((value: boolean) => void) | null)}
	<label
		class="flex items-center justify-between gap-4 {onchange
			? 'cursor-pointer'
			: 'cursor-default opacity-60'}"
	>
		<span class="text-body-1 font-bold">{label}</span>
		<input
			type="checkbox"
			{checked}
			disabled={onchange === null}
			onchange={(event) => onchange?.(event.currentTarget.checked)}
			class="peer sr-only"
		/>
		<span
			aria-hidden="true"
			class="grid size-7 shrink-0 place-items-center rounded-full border-3 border-white text-pink transition-colors peer-checked:bg-white peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-white"
		>
			<IconCheck class="size-4 {checked ? '' : 'invisible'}" />
		</span>
	</label>
{/snippet}

{#if visible}
	<aside
		aria-labelledby="cookie-banner-title"
		class="fixed left-5 z-30 w-[min(36rem,calc(100vw-2.5rem))] overflow-hidden rounded-3xl bg-pink p-6 text-white lg:left-12 {raised
			? 'bottom-17'
			: 'bottom-5'}"
	>
		<ShapeCookies class="pointer-events-none absolute right-0 bottom-0 text-green" />

		<div class="relative flex flex-col gap-6">
			<h2 id="cookie-banner-title" class="text-h4">Ce site utilise des cookies ! 🍪</h2>
			<div class={RULE}></div>

			{#if view === 'intro'}
				{#if text}
					<p class="text-body-1 py-6 whitespace-pre-line">{text}</p>
				{/if}
				<div class={RULE}></div>
				<div class="flex flex-wrap justify-end gap-3">
					<button
						type="button"
						onclick={openPreferences}
						class="{BUTTON} text-white hover:bg-white/15"
					>
						Modifier mes préférences
					</button>
					<button
						type="button"
						onclick={cookieConsent.acceptAll}
						class="{BUTTON} bg-white text-pink hover:bg-transparent hover:text-white"
					>
						Tout accepter
					</button>
				</div>
			{:else}
				<div class="flex flex-col gap-1.5 py-6">
					{#each categories as category (category.key)}
						{#if category.key === 'necessary'}
							<!-- Always on: the site cannot work without them, so there is nothing to consent to. -->
							{@render toggle(category.label, true, null)}
						{:else}
							{@const key = category.key}
							{@render toggle(
								category.label,
								choice[key],
								(value) => (choice = { ...choice, [key]: value })
							)}
						{/if}
					{/each}
				</div>
				<div class={RULE}></div>
				<div class="flex justify-end">
					<button
						type="button"
						bind:this={saveButton}
						onclick={() => cookieConsent.save(choice)}
						class="{BUTTON} text-white hover:bg-white/15"
					>
						Enregistrer mes préférences
					</button>
				</div>
			{/if}

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
