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
	import IconLink from '$lib/components/svg/IconLink.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import SearchModal from '$lib/components/layout/SearchModal.svelte';

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
	let searchQuery = $state('');
	let searchModalOpen = $state(false);

	// The header control is only the entry point: the query lives in the modal,
	// which focuses its own field on open.
	const openSearch = () => {
		searchModalOpen = true;
		closeMenu();
	};

	$effect(() => {
		if (!searchModalOpen) searchQuery = '';
	});

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
	<div class="max-w-360 mx-auto flex items-center gap-x-3 lg:gap-x-12 px-base py-3 text-blue">
		<a href="/" onclick={closeMenu} class="shrink-0 {menuOpen ? 'max-lg:hidden' : ''}" aria-label={header.siteTitle}>
			<Img
				image={header.logo}
				alt={header.logo.alt ?? header.siteTitle}
				sizes="10rem"
				class="h-7.5 w-auto"
				loading="eager"
			/>
		</a>
		<nav aria-label="Menu principal" class="max-lg:hidden lg:ml-auto {menuOpen ? 'max-lg:block!' : ''}">
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

		<div class="w-60 flex justify-end max-lg:ml-auto">
			<button
				type="button"
				id="search-button"
				onclick={openSearch}
				aria-haspopup="dialog"
				aria-expanded={searchModalOpen}
				aria-label="Rechercher"
				class="group flex items-center p-1 rounded-full transition-colors shrink hover:bg-grey-light focus-visible:ring-2 focus-visible:ring-blue focus:outline-none {searchModalOpen
					? 'bg-grey-light'
					: ''}"
			>
				<span
					aria-hidden="true"
					class="text-body-2 font-bold text-left truncate min-w-0 transition-all duration-300 ease-out {searchModalOpen
						? 'w-48 opacity-100 pl-3'
						: 'w-0 opacity-0 group-hover:w-48 group-hover:opacity-100 group-hover:pl-3'} {searchQuery
						? 'text-blue'
						: 'text-blue/50'}"
				>
					{searchQuery || 'Rechercher...'}
				</span>
				<span
					class="shrink-0 p-2 rounded-full transition-colors group-hover:bg-blue group-hover:text-white {searchModalOpen
						? 'bg-blue text-white'
						: ''}"
				>
					<IconSearch class="shrink-0" />
				</span>
			</button>
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
				<span class="max-lg:hidden">Menu</span>
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
			class="max-w-360 mx-auto px-base py-12 grid grid-cols-1 lg:grid-cols-5 lg:items-start gap-x-8 gap-y-10"
			transition:slide={{ duration: 300 }}
			aria-label="Menu secondaire"
		>
			{#each header.secondaryMenu as column, i (i)}
				<div>
					{#if column.title}
						<p class="text-body-2 font-bold text-blue pb-3 mb-6 lg:border-b-2">{column.title}</p>
					{/if}
					<div class="flex flex-col gap-y-6">
						{#each column.groups as group, j (j)}
							{@const hasLevel2 = group.links.some((link) => link.level === 2)}
							<div>
								{#if group.title}
									<p class="text-label leading-none text-blue mb-0.75">{group.title}</p>
								{/if}
								<ul class="flex flex-col">
									{#each group.links as link (link)}
										<li class="leading-none {hasLevel2 && link.level !== 2 ? 'mb-0.75' : ''} {link.level === 2 ? 'pl-8' : ''}">
											<a
												href={link.url}
												onclick={closeMenu}
												target={isExternal(link.url) ? '_blank' : undefined}
												rel={isExternal(link.url) ? 'noopener noreferrer' : undefined}
												class="text-body-2 leading-none {link.level === 2
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

			<div class="flex flex-col bg-green rounded-2xl p-4 -m-4">
				{#if header.externalLinksTitle}
					<p class="text-body-2 font-bold pb-3 mb-6 lg:border-b-2">{header.externalLinksTitle}</p>
				{/if}
				{#if header.externalLinks.length > 0}
					<ul class="flex flex-col gap-y-3 mb-6">
						{#each header.externalLinks as link (link)}
							<li class="leading-none">
								<a
									href={link.url}
									target="_blank"
									rel="noopener noreferrer"
									class="text-body-2 leading-none flex items-center gap-x-2"
								>
									<IconLink /> <span class="underline">{link.label}</span>
								</a>
							</li>
						{/each}
					</ul>
				{/if}

				{#if header.socialLinks.length > 0}
					<ul class="flex flex-col gap-y-3">
						{#each header.socialLinks as social (social.platform)}
							{@const Icon = socialIcons[social.platform]}
							<li>
								<a
									href={social.url}
									target="_blank"
									rel="noopener noreferrer"
									class="flex items-center gap-x-2 text-body-2 leading-none hover:text-blue transition-colors"
								>
									<span class="flex items-center justify-center h-8 w-8 rounded-full bg-white text-blue shrink-0">
										<Icon height="24" width="24" class="h-6 w-6" />
									</span>
									<span>{socialLabels[social.platform]}</span>
								</a>
							</li>
						{/each}
					</ul>
				{/if}
			</div>
		</nav>
	{/if}
</header>

<SearchModal bind:open={searchModalOpen} bind:value={searchQuery} />
