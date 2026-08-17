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
	import IconSearch from '$lib/components/svg/IconSearch.svelte';

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
	let headerEl = $state<HTMLElement>();
	let searchInput = $state<HTMLInputElement>();
	let searchOpen = $state(false);

	const openSearch = () => {
		searchOpen = true;
		searchInput?.focus();
	};

	const toggleMenu = () => {
		menuOpen = !menuOpen;
	};

	const closeMenu = () => {
		menuOpen = false;
	};

	$effect(() => {
		if (!menuOpen) return;
		const handlePointerDown = (event: PointerEvent) => {
			if (headerEl && !headerEl.contains(event.target as Node)) {
				closeMenu();
			}
		};
		document.addEventListener('pointerdown', handlePointerDown);
		return () => document.removeEventListener('pointerdown', handlePointerDown);
	});

	const isExternal = (url: string | null): boolean => !!url && /^https?:\/\//.test(url);
</script>

<header
	bind:this={headerEl}
	class="fixed top-0 inset-x-0 z-10 rounded-b-4xl bg-white transition-shadow has-[#burger-menu-button:hover]:shadow {menuOpen ? 'shadow' : ''}"
>
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
							class="text-body-2 font-bold underline decoration-transparent hover:decoration-current transition-colors"
						>
							{item.label}
						</a>
					</li>
				{/each}
			</ul>
		</nav>

		<div class="w-60 flex justify-end">
			<div role="search" class="group flex items-center has-[#search-button:hover]:bg-grey-light has-[#search-input:focus]:bg-grey-light transition-colors p-1 rounded-full focus-within:ring-2 focus-within:ring-blue shrink">
				<input
					bind:this={searchInput}
					id="search-input"
					type="search"
					placeholder="Rechercher..."
					aria-label="Rechercher"
					tabindex={searchOpen ? undefined : -1}
					onblur={() => (searchOpen = false)}
					class="text-body-2 font-bold text-blue bg-transparent border-0 min-w-0 w-0 opacity-0 group-has-[#search-button:hover]:w-48 group-has-[#search-button:hover]:opacity-100 group-has-[#search-button:hover]:mr-3 focus:w-48 focus:opacity-100 focus:mr-3 transition-all duration-300 ease-out placeholder:text-blue/50 focus:ring-0 focus:outline-none peer"
				/>
				<button
					type="button"
					id="search-button"
					onclick={openSearch}
					aria-controls="search-input"
					aria-expanded={searchOpen}
					class="shrink-0 p-2 rounded-full hover:bg-blue hover:text-white transition-colors peer-focus:bg-white"
					aria-label="Rechercher"
				>
					<IconSearch class="shrink-0" />
				</button>
			</div>
		</div>

		<button
			type="button"
			id="burger-menu-button"
			onclick={toggleMenu}
			aria-expanded={menuOpen}
			aria-controls="burger-menu"
			class="text-body-2 font-bold px-3 py-2 flex items-center gap-x-3 shrink-0 justify-end rounded-full hover:bg-blue hover:text-white transition-colors"
			aria-label={menuOpen ? 'Fermer le menu' : 'Ouvrir le menu'}
		>
				<span>Menu</span>
			{#if menuOpen}
				<IconClose class="shrink-0 w-7 h-auto" width="28" height="29" />
			{:else}
				<IconHamburger class="shrink-0" width="28" height="29" />
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
						<p class="text-body-2 font-bold text-blue pb-3 mb-6 border-b-2">{column.title}</p>
					{/if}
					<div class="flex flex-col">
						{#each column.groups as group, j (j)}
							{@const hasLevel2 = group.links.some((link) => link.level === 2)}
							<div>
								{#if group.title}
									<p class="text-label text-blue mb-0.75">{group.title}</p>
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
													: hasLevel2
														? 'font-bold'
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
