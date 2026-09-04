<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import type { Footer, NewsletterResponse, NewsletterStatus } from '$lib/interfaces/global';
	import FooterBackground from '$lib/components/svg/FooterBackground.svelte';
	import FooterBackgroundMobile from '$lib/components/svg/FooterBackgroundMobile.svelte';
	import IconEmail from '$lib/components/svg/IconEmail.svelte';
	import IconPhone from '$lib/components/svg/IconPhone.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import { socialLabels } from '$lib/utils/socials';

	interface Props {
		footer: Footer;
	}

	let { footer }: Props = $props();

	const address = $derived(footer.address);
	// Postal code and city share a line; either can be empty, so they are joined rather than
	// laid out with a fixed separator. `region`/`country` are deliberately not shown here.
	const cityLine = $derived([address.postalCode, address.locality].filter(Boolean).join(' '));
	const hasAddress = $derived(Boolean(address.street || cityLine));

	const newsletter = $derived(footer.newsletter);

	let newsletterEmail = $state('');
	let newsletterStatus = $state<NewsletterStatus | 'idle'>('idle');
	let submitting = $state(false);

	// Every message is CMS-managed, so the endpoint only reports which one to show.
	const newsletterMessage = $derived.by(() => {
		switch (newsletterStatus) {
			case 'ok':
				return newsletter.messages.success;
			case 'invalidEmail':
				return newsletter.messages.invalidEmail;
			case 'error':
				return newsletter.messages.error;
			default:
				return null;
		}
	});

	// Posts to our own origin rather than to the provider: `/api/newsletter` forwards it
	// server-side, which is what lets us answer here instead of in a new tab.
	const onNewsletterSubmit = async (event: SubmitEvent) => {
		event.preventDefault();
		if (submitting) return;

		submitting = true;
		newsletterStatus = 'idle';

		try {
			const response = await fetch('/api/newsletter', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ email: newsletterEmail })
			});
			const result: NewsletterResponse = await response.json();
			newsletterStatus = result.status;
		} catch {
			// Offline, or an answer that was not the JSON the endpoint always returns.
			newsletterStatus = 'error';
		} finally {
			submitting = false;
		}

		if (newsletterStatus === 'ok') newsletterEmail = '';
	};
</script>

{#snippet columnTitle(title: string)}
	<p class="font-bold mb-lh">{title}</p>
{/snippet}

<footer class="text-body-2 relative isolate bg-grey-light">
	<!-- The artwork is capped to the content column; past it the two shapes running off its
		 left and right edges continue as flat bands to the viewport edges. -->
	<div
		aria-hidden="true"
		class="absolute inset-0 -z-10 lg:grid lg:grid-cols-[1fr_min(100%,var(--content-max))_1fr]"
	>
		<div class="max-lg:hidden bg-yellow"></div>
		<FooterBackground class="hidden lg:block w-full h-full" />
		<div class="max-lg:hidden bg-green"></div>
		<FooterBackgroundMobile class="lg:hidden w-full h-full" />
	</div>

	<div
		class="w-full max-w-360 mx-auto px-base pt-6 lg:pt-9 grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-x-4 lg:gap-x-8 gap-y-10"
	>
		<div>
			{#if address.name}
				{@render columnTitle(address.name)}
			{/if}

			{#if hasAddress}
				{#snippet addressLines()}
					<span class="block">{address.street}</span>
					{#if cityLine}<span class="block">{cityLine}</span>{/if}
				{/snippet}
				<address class="not-italic mb-lh">
					{#if address.mapUrl}
						<a
							href={address.mapUrl}
							target="_blank"
							rel="noopener noreferrer"
							class="underline decoration-transparent hover:decoration-current transition-colors"
						>
							{@render addressLines()}
						</a>
					{:else}
						{@render addressLines()}
					{/if}
				</address>
			{/if}

			{#if footer.email || footer.phoneUrl}
				<ul class="flex flex-col">
					{#if footer.email}
						<li>
							<a href="mailto:{footer.email}" class="group">
								<span
									class="underline decoration-transparent group-hover:decoration-current transition-colors"
									>{footer.email}</span
								>
							</a>
						</li>
					{/if}
					{#if footer.phoneUrl}
						<li>
							<a href={footer.phoneUrl} class="group">
								<span
									class="underline decoration-transparent group-hover:decoration-current transition-colors"
									>{footer.phone}</span
								>
							</a>
						</li>
					{/if}
				</ul>
			{/if}
		</div>

		{#if footer.logoEntrepriseFormatrice}
			<Img
				image={footer.logoEntrepriseFormatrice}
				sizes="10rem"
				class="lg:hidden h-auto w-full max-w-35"
			/>
		{/if}

		<div>
			{#if footer.socialsTitle}
				{@render columnTitle(footer.socialsTitle)}
			{/if}
			{#if footer.socialLinks.length > 0}
				<ul class="flex flex-col">
					{#each footer.socialLinks as social, i (`${social.platform}-${i}`)}
						<li>
							<a
								href={social.url}
								target="_blank"
								rel="noopener noreferrer"
								class="underline decoration-transparent hover:decoration-current transition-colors"
							>
								{socialLabels[social.platform]}
							</a>
						</li>
					{/each}
				</ul>
			{/if}
		</div>

		<nav aria-label="Menu du pied de page">
			{#if footer.menuTitle}
				{@render columnTitle(footer.menuTitle)}
			{/if}
			{#if footer.menuLinks.length > 0}
				<ul class="flex flex-col">
					{#each footer.menuLinks as link (link)}
						<li>
							<a
								href={link.url}
								target={link.target ?? undefined}
								rel={link.target === '_blank' ? 'noopener noreferrer' : undefined}
								class="underline decoration-transparent hover:decoration-current transition-colors"
							>
								{link.label}
							</a>
						</li>
					{/each}
				</ul>
			{/if}
		</nav>

		<div class="col-span-2 lg:col-span-1 xl:col-span-2">
			{#if newsletter.title}
				{@render columnTitle(newsletter.title)}
			{/if}
			<!-- Same pill as the header's search control, but permanently expanded and on white. -->
			<!-- `novalidate`: the CMS owns the wording of the invalid-address message, so the
				 browser's own validation bubble would say the same thing twice, in its language. -->
			<form
				onsubmit={onNewsletterSubmit}
				novalidate
				class="flex items-center p-1 rounded-full bg-white transition-shadow focus-within:ring-2 focus-within:ring-black"
			>
				<label class="sr-only" for="footer-newsletter-email">
					{newsletter.submitLabel ?? "S'inscrire à la newsletter"}
				</label>
				<input
					id="footer-newsletter-email"
					type="email"
					required
					autocomplete="email"
					disabled={submitting}
					aria-invalid={newsletterStatus === 'invalidEmail' || newsletterStatus === 'error'}
					aria-describedby="footer-newsletter-message"
					bind:value={newsletterEmail}
					placeholder={newsletter.placeholder ?? ''}
					class="min-w-0 flex-1 border-0 bg-transparent pl-3 py-1 font-bold placeholder-beige focus:border-0 focus:ring-0 focus:outline-none disabled:opacity-60"
				/>
				<button
					type="submit"
					disabled={submitting}
					aria-label={newsletter.submitLabel ?? "S'inscrire à la newsletter"}
					class="shrink-0 p-2.5 rounded-full bg-blue text-white transition-opacity disabled:opacity-60"
				>
					<IconEmail class="shrink-0 w-6.25 h-6.25" />
				</button>
			</form>

			<!-- Always in the DOM so screen readers announce the message when it appears
				 rather than when the region itself is inserted. -->
			<p
				id="footer-newsletter-message"
				aria-live="polite"
				class="text-label mt-2 font-bold {newsletterStatus === 'ok' ? 'text-black' : 'text-red'}"
			>
				{newsletterMessage ?? ''}
			</p>
		</div>
	</div>
	<div
		class="w-full max-w-360 mx-auto flex items-center justify-center lg:justify-between gap-x-4 px-base pb-12.5 lg:pb-9 mt-12.5 lg:mt-2.5"
	>
		<div class="bg-white rounded-full py-2.5 lg:py-5 px-5 lg:px-10">
			<Img image={footer.logo} sizes="10rem" class="h-6 lg:h-12 w-auto max-w-full" />
		</div>
		{#if footer.logoEntrepriseFormatrice}
			<Img
				image={footer.logoEntrepriseFormatrice}
				sizes="10rem"
				class="max-lg:hidden h-55 w-auto max-w-full"
			/>
		{/if}
	</div>
</footer>
