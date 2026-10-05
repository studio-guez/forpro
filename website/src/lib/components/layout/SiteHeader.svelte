<script lang="ts">
	/* eslint-disable svelte/no-navigation-without-resolve -- hrefs come from the CMS */
	import type { Header } from '$lib/interfaces/global';
	import IconHamburger from '$lib/components/svg/IconHamburger.svelte';
	import IconClose from '$lib/components/svg/IconClose.svelte';
	import IconSearch from '$lib/components/svg/IconSearch.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import SearchModal from '$lib/components/layout/SearchModal.svelte';
	import { lockPageScroll } from '$lib/utils/smoothScroll';
	import SiteSecondaryMenuDesktop from '$lib/components/layout/SiteSecondaryMenuDesktop.svelte';
	import SiteSecondaryMenuMobile from '$lib/components/layout/SiteSecondaryMenuMobile.svelte';

	interface Props {
		header: Header;
	}

	let { header }: Props = $props();

	let menuOpen = $state(false);
	let headerEl = $state<HTMLElement>();
	let burgerButtonEl = $state<HTMLButtonElement>();
	let searchQuery = $state('');
	let searchModalOpen = $state(false);

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
		return lockPageScroll();
	});

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

	// Focus goes back to the toggle, otherwise it is left on a panel that no longer exists.
	$effect(() => {
		if (!menuOpen) return;
		const handleKeydown = (event: KeyboardEvent) => {
			if (event.key !== 'Escape') return;
			closeMenu();
			burgerButtonEl?.focus();
		};
		document.addEventListener('keydown', handleKeydown);
		return () => document.removeEventListener('keydown', handleKeydown);
	});
</script>

<header
	bind:this={headerEl}
	class="fixed top-0 inset-x-0 z-10 flex flex-col max-h-dvh rounded-b-4xl bg-white transition-shadow has-[#burger-menu-button:hover]:shadow {menuOpen
		? 'shadow'
		: ''}"
>
	<div
		class="w-full max-w-360 mx-auto shrink-0 flex items-center gap-x-3 lg:gap-x-12 px-base py-3 text-blue"
	>
		<a
			href="/"
			onclick={closeMenu}
			class="shrink-0 {menuOpen ? 'max-lg:hidden' : ''}"
			aria-label={header.siteTitle}
		>
			<Img
				image={header.logo}
				alt={header.logo.alt ?? header.siteTitle}
				sizes="10rem"
				class="h-7.5 w-auto"
				loading="eager"
			/>
		</a>
		<nav
			aria-label="Menu principal"
			class="max-lg:hidden lg:ml-auto {menuOpen ? 'max-lg:block!' : ''}"
		>
			<ul class="flex items-center gap-x-6 lg:gap-x-12">
				{#each header.mainMenu as item (item)}
					<li class="flex items-center">
						<a
							href={item.url}
							onclick={closeMenu}
							target={item.target ?? undefined}
							rel={item.target === '_blank' ? 'noopener noreferrer' : undefined}
							class="text-body-2 font-bold underline decoration-transparent hover:decoration-current transition-colors text-trim"
						>
							{item.label}
						</a>
					</li>
				{/each}
			</ul>
		</nav>

		<div class="lg:w-60 flex justify-end max-lg:ml-auto">
			<button
				type="button"
				id="search-button"
				onclick={openSearch}
				aria-haspopup="dialog"
				aria-expanded={searchModalOpen}
				aria-label="Rechercher"
				class="group flex items-center p-1 rounded-full transition-colors shrink hover:lg:bg-grey-light focus-visible:ring-2 focus-visible:ring-blue focus:outline-none {searchModalOpen
					? 'bg-grey-light'
					: ''}"
			>
				<span
					aria-hidden="true"
					class="max-lg:hidden text-body-2 font-bold text-left truncate-x min-w-0 transition-all duration-300 ease-out text-trim {searchModalOpen
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
			bind:this={burgerButtonEl}
			onclick={toggleMenu}
			aria-expanded={menuOpen}
			aria-controls={menuOpen ? 'burger-menu-desktop burger-menu-mobile' : undefined}
			class="text-body-2 font-bold px-3 py-2 flex items-center gap-x-3 shrink-0 justify-end rounded-full hover:bg-blue hover:text-white transition-colors"
			aria-label={menuOpen ? 'Fermer le menu' : 'Ouvrir le menu'}
		>
			<span class="max-lg:hidden text-trim">Menu</span>
			{#if menuOpen}
				<IconClose class="shrink-0 w-7.25 h-7.25" />
			{:else}
				<IconHamburger class="shrink-0 w-7.25 h-7.25" />
			{/if}
		</button>
	</div>

	{#if menuOpen}
		<SiteSecondaryMenuDesktop {header} onNavigate={closeMenu} />
		<SiteSecondaryMenuMobile {header} onNavigate={closeMenu} />
	{/if}
</header>

<SearchModal bind:open={searchModalOpen} bind:value={searchQuery} />
