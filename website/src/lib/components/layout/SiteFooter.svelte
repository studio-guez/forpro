<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import type { Footer } from '$lib/interfaces/global';
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

	let newsletterEmail = $state('');

	// No subscription endpoint exists yet (the CMS holds no provider settings): the field is
	// wired up to nothing on purpose rather than posting somewhere that would silently fail.
	const onNewsletterSubmit = (event: SubmitEvent) => {
		event.preventDefault();
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
		class="w-full max-w-360 mx-auto px-5 lg:px-9 pt-6 lg:pt-9 grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-x-4 lg:gap-x-8 gap-y-10"
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
			{#if footer.newsletterTitle}
				{@render columnTitle(footer.newsletterTitle)}
			{/if}
			<!-- Same pill as the header's search control, but permanently expanded and on white. -->
			<form
				onsubmit={onNewsletterSubmit}
				class="flex items-center p-1 rounded-full bg-white transition-shadow focus-within:ring-2 focus-within:ring-black"
			>
				<label class="sr-only" for="footer-newsletter-email">Votre adresse e-mail</label>
				<input
					id="footer-newsletter-email"
					type="email"
					required
					autocomplete="email"
					bind:value={newsletterEmail}
					placeholder="Votre e-mail..."
					class="min-w-0 flex-1 border-0 bg-transparent pl-3 py-1 font-bold placeholder-beige focus:border-0 focus:ring-0 focus:outline-none"
				/>
				<button
					type="submit"
					aria-label="S'inscrire à la newsletter"
					class="shrink-0 p-2.5 rounded-full bg-blue text-white"
				>
					<IconEmail class="shrink-0 w-6.25 h-6.25" />
				</button>
			</form>
		</div>
	</div>
	<div
		class="w-full max-w-360 mx-auto flex items-center justify-center lg:justify-between gap-x-4 px-5 lg:px-9 pb-12.5 lg:pb-9 max-lg:mt-12.5"
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
