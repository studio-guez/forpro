<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import type { Header, SocialPlatform } from '$lib/interfaces/global';
	import IconFacebook from '$lib/components/svg/IconFacebook.svelte';
	import IconInstagram from '$lib/components/svg/IconInstagram.svelte';
	import IconLinkedin from '$lib/components/svg/IconLinkedin.svelte';
	import IconYoutube from '$lib/components/svg/IconYoutube.svelte';
	import IconTiktok from '$lib/components/svg/IconTiktok.svelte';
	import IconSnapchat from '$lib/components/svg/IconSnapchat.svelte';
	import IconX from '$lib/components/svg/IconX.svelte';

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

<header class="fixed top-0 inset-x-0 z-50">
	<div class="relative z-10 bg-white flex items-center justify-between gap-6 px-base py-4">
		<a href="/" onclick={closeMenu} class="shrink-0" aria-label={header.siteTitle}>
			{#if header.logo}
				<img
					src={header.logo.url}
					srcset={header.logo.srcset}
					sizes="10rem"
					width={header.logo.width}
					height={header.logo.height}
					alt={header.logo.alt ?? header.siteTitle}
					class="h-10 w-auto"
					loading="eager"
				/>
			{:else}
				<span class="text-h4">{header.siteTitle}</span>
			{/if}
		</a>

		<nav aria-label="Menu principal" class="hidden md:block">
			<ul class="flex items-center gap-8">
				{#each header.mainMenu as item (item)}
					<li>
						<a
							href={item.url}
							onclick={closeMenu}
							target={isExternal(item.url) ? '_blank' : undefined}
							rel={isExternal(item.url) ? 'noopener noreferrer' : undefined}
							class="text-body-2 font-bold hover:text-blue transition-colors"
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
			class="shrink-0 flex flex-col justify-center gap-1.5 w-10 h-10 cursor-pointer"
			aria-label={menuOpen ? 'Fermer le menu' : 'Ouvrir le menu'}
		>
			<span
				class="block h-0.75 w-full bg-black transition-transform {menuOpen
					? 'translate-y-1.125 rotate-45'
					: ''}"
			></span>
			<span
				class="block h-0.75 w-full bg-black transition-transform {menuOpen
					? '-translate-y-1.125 -rotate-45'
					: ''}"
			></span>
		</button>
	</div>

	{#if menuOpen}
		<div
			id="burger-menu"
			class="absolute inset-x-0 top-full max-h-[calc(100dvh-100%)] overflow-y-auto bg-white border-t border-grey-light"
		>
			<nav
				aria-label="Menu secondaire"
				class="px-base py-10 grid grid-cols-1 md:grid-cols-5 gap-x-8 gap-y-10"
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
		</div>
	{/if}
</header>
