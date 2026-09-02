<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import type { Footer } from '$lib/interfaces/global';
	import FooterBackground from '$lib/components/svg/FooterBackground.svelte';
	import IconEmail from '$lib/components/svg/IconEmail.svelte';
	import IconPhone from '$lib/components/svg/IconPhone.svelte';
	import { socialIcons, socialLabels } from '$lib/utils/socials';

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
	<p class="font-bold mb-[1em]">{title}</p>
{/snippet}

<footer class="text-body-2 relative isolate bg-beige">
	<!-- The artwork is capped to the content column; past it the two shapes running off its
		 left and right edges continue as flat bands to the viewport edges. -->
	<div
		aria-hidden="true"
		class="absolute inset-0 -z-10 grid grid-cols-[1fr_min(100%,var(--content-max))_1fr]"
	>
		<div class="bg-yellow"></div>
		<FooterBackground class="block w-full h-full" />
		<div class="bg-green"></div>
	</div>

	<div
		class="w-full max-w-360 mx-auto px-5 lg:px-9 py-12 lg:py-18 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-10"
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
				<address class="not-italic mb-[1em]">
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
							<a
								href="mailto:{footer.email}"
								class="leading-none group"
							>
								<span class="underline decoration-transparent group-hover:decoration-current transition-colors"
									>{footer.email}</span
								>
							</a>
						</li>
					{/if}
					{#if footer.phoneUrl}
						<li>
							<a
								href={footer.phoneUrl}
								class="leading-none group"
							>
								<span class="underline decoration-transparent group-hover:decoration-current transition-colors"
									>{footer.phone}</span
								>
							</a>
						</li>
					{/if}
				</ul>
			{/if}
		</div>

		<div>
			{#if footer.socialsTitle}
				{@render columnTitle(footer.socialsTitle)}
			{/if}
			{#if footer.socialLinks.length > 0}
				<ul class="flex flex-wrap gap-3">
					{#each footer.socialLinks as social, i (`${social.platform}-${i}`)}
						{@const Icon = socialIcons[social.platform]}
						<li>
							<a
								href={social.url}
								target="_blank"
								rel="noopener noreferrer"
								aria-label={socialLabels[social.platform]}
								class="flex items-center justify-center h-11 w-11 rounded-full bg-white text-blue hover:bg-blue hover:text-white transition-colors"
							>
								<Icon class="h-6 w-6" />
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
				<ul class="flex flex-col gap-y-3">
					{#each footer.menuLinks as link (link)}
						<li class="leading-none">
							<a
								href={link.url}
								target={link.target ?? undefined}
								rel={link.target === '_blank' ? 'noopener noreferrer' : undefined}
								class="leading-none underline decoration-transparent hover:decoration-current transition-colors"
							>
								{link.label}
							</a>
						</li>
					{/each}
				</ul>
			{/if}
		</nav>

		<div>
			{#if footer.newsletterTitle}
				{@render columnTitle(footer.newsletterTitle)}
			{/if}
			<!-- Same pill as the header's search control, but permanently expanded and on white. -->
			<form
				onsubmit={onNewsletterSubmit}
				class="group flex items-center p-1 rounded-full bg-white transition-shadow focus-within:ring-2 focus-within:ring-blue"
			>
				<label class="sr-only" for="footer-newsletter-email">Votre adresse e-mail</label>
				<input
					id="footer-newsletter-email"
					type="email"
					required
					autocomplete="email"
					bind:value={newsletterEmail}
					placeholder="Votre e-mail..."
					class="min-w-0 flex-1 border-0 bg-transparent pl-3 py-1 font-bold text-blue placeholder-blue/50 focus:border-0 focus:ring-0 focus:outline-none"
				/>
				<button
					type="submit"
					aria-label="S'inscrire à la newsletter"
					class="shrink-0 p-2 rounded-full transition-colors hover:bg-blue hover:text-white group-focus-within:bg-blue group-focus-within:text-white"
				>
					<IconEmail class="shrink-0 w-7.25 h-7.25" />
				</button>
			</form>
		</div>
	</div>
</footer>
