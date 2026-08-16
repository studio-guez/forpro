<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import { slide } from 'svelte/transition';
	import type { Header, SocialPlatform } from '$lib/interfaces/global';
	import IconFacebook from '$lib/components/svg/IconFacebook.svelte';
	import IconInstagram from '$lib/components/svg/IconInstagram.svelte';
	import IconLinkedin from '$lib/components/svg/IconLinkedin.svelte';
	import IconYoutube from '$lib/components/svg/IconYoutube.svelte';
	import IconTiktok from '$lib/components/svg/IconTiktok.svelte';
	import IconSnapchat from '$lib/components/svg/IconSnapchat.svelte';
	import IconX from '$lib/components/svg/IconX.svelte';	
	import IconHamburger from '$lib/components/svg/IconHamburger.svelte';
	import IconClose from '$lib/components/svg/IconClose.svelte';

	interface Props {
		header: Header;
	}

	let { header }: Props = $props();

	const socialIcons = {
		facebook: IconFacebook,
		instagram: IconInstagram,
		linkedin: IconLinkedin,
		youtube: IconYoutube,
		tiktok: IconTiktok,
		snapchat: IconSnapchat,
		x: IconX
	} satisfies Record<SocialPlatform, unknown>;

	const socialLabels: Record<SocialPlatform, string> = {
		facebook: 'Facebook',
		instagram: 'Instagram',
		linkedin: 'LinkedIn',
		youtube: 'YouTube',
		tiktok: 'TikTok',
		snapchat: 'Snapchat',
		x: 'X'
	};

	let menuOpen = $state(false);

	const toggleMenu = () => {
		menuOpen = !menuOpen;
	};

	const closeMenu = () => {
		menuOpen = false;
	};

	const isExternal = (url: string | null): boolean => !!url && /^https?:\/\//.test(url);
</script>

<header class="fixed top-0 inset-x-0 z-10 rounded-b-4xl shadow-xl bg-white">
	<div class="max-w-360 mx-auto flex items-center gap-x-12 px-base py-3 text-blue">
		<a href="/" onclick={closeMenu} class="shrink-0" aria-label={header.siteTitle}>
			<img
				src={header.logo.url}
				srcset={header.logo.srcset}
				sizes="10rem"
				width={header.logo.width}
				height={header.logo.height}
				alt={header.logo.alt ?? header.siteTitle}
				class="h-7.5 w-auto"
				loading="eager"
			/>
		</a>
		<nav aria-label="Menu principal" class="max-md:hidden ml-auto">
			<ul class="flex items-center gap-x-12">
				{#each header.mainMenu as item (item)}
					<li class="flex items-center">
						<a
							href={item.url}
							onclick={closeMenu}
							target={isExternal(item.url) ? '_blank' : undefined}
							rel={isExternal(item.url) ? 'noopener noreferrer' : undefined}
							class="text-body-2 font-bold hover:opacity-50 transition-opacity"
						>
							{item.label}
						</a>
					</li>
				{/each}
			</ul>
		</nav>

		<button
			type="button"
			onclick={toggleMenu}
			aria-expanded={menuOpen}
			aria-controls="burger-menu"
			class="text-body-2 font-bold flex items-center gap-x-3 hover:opacity-50 transition-opacity w-28 shrink-0 justify-end"
			aria-label={menuOpen ? 'Fermer le menu' : 'Ouvrir le menu'}
		>
			{#if menuOpen}
				<span>Fermer</span><IconClose class="shrink-0 w-7 h-auto" width="28" height="29" />
			{:else}
				<span>Menu</span><IconHamburger class="shrink-0" />
			{/if}
		</button>
	</div>

	{#if menuOpen}
		<nav
			id="burger-menu"
			class="max-w-360 mx-auto px-base py-12 grid grid-cols-1 md:grid-cols-5 gap-x-8 gap-y-10"
			transition:slide={{ duration: 300 }}
			aria-label="Menu secondaire"
		>
			{#each header.secondaryMenu as column, i (i)}
				<div>
					{#if column.title}
						<p class="text-label font-bold text-grey-dark mb-4">{column.title}</p>
					{/if}
					<div class="flex flex-col gap-6">
						{#each column.groups as group, j (j)}
							<div>
								{#if group.title}
									<p class="text-body-2 font-bold mb-2">{group.title}</p>
								{/if}
								<ul class="flex flex-col gap-1">
									{#each group.links as link (link)}
										<li class={link.level === 2 ? 'pl-4' : ''}>
											<a
												href={link.url}
												onclick={closeMenu}
												target={isExternal(link.url) ? '_blank' : undefined}
												rel={isExternal(link.url) ? 'noopener noreferrer' : undefined}
												class="text-label {link.level === 2
													? 'text-grey-dark'
													: ''} hover:text-blue transition-colors"
											>
												{link.label}
											</a>
										</li>
									{/each}
								</ul>
							</div>
						{/each}
					</div>
				</div>
			{/each}

			<div class="flex flex-col gap-6">
				{#if header.externalLinksTitle}
					<p class="text-label font-bold text-grey-dark">{header.externalLinksTitle}</p>
				{/if}
				{#if header.externalLinks.length > 0}
					<ul class="flex flex-col gap-1">
						{#each header.externalLinks as link (link)}
							<li>
								<a
									href={link.url}
									target="_blank"
									rel="noopener noreferrer"
									class="text-label hover:text-blue transition-colors"
								>
									{link.label}
								</a>
							</li>
						{/each}
					</ul>
				{/if}

				{#if header.socialLinks.length > 0}
					<ul class="flex items-center gap-4">
						{#each header.socialLinks as social (social.platform)}
							{@const Icon = socialIcons[social.platform]}
							<li>
								<a
									href={social.url}
									target="_blank"
									rel="noopener noreferrer"
									aria-label={socialLabels[social.platform]}
									class="block h-6 w-6 hover:text-blue transition-colors"
								>
									<Icon />
								</a>
							</li>
						{/each}
					</ul>
				{/if}
			</div>
		</nav>
	{/if}
</header>
