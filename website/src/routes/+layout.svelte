<script lang="ts">
	import '../app.css';
	import 'lenis/dist/lenis.css';
	import { onMount } from 'svelte';
	import { initSmoothScroll } from '$lib/utils/smoothScroll';
	import SiteHeader from '$lib/components/layout/SiteHeader.svelte';
	import SiteMarquee from '$lib/components/layout/SiteMarquee.svelte';
	import SiteFooter from '$lib/components/layout/SiteFooter.svelte';
	import CookieBanner from '$lib/components/layout/CookieBanner.svelte';
	import JsonLd from '$lib/components/layout/JsonLd.svelte';
	import { IS_PROD } from '$lib/env';
	import { PUBLIC_CMS_BASE_URL } from '$env/static/public';
	import fontRegular from '$lib/assets/fonts/Jungka_Webfonts/Jungka-Regular.woff2?url';
	import fontBold from '$lib/assets/fonts/Jungka_Webfonts/Jungka-Bold.woff2?url';
	import type { LayoutData } from './$types';

	interface Props {
		data: LayoutData;
		children?: import('svelte').Snippet;
	}

	let { data, children }: Props = $props();

	onMount(initSmoothScroll);

	let bannerDismissed = $derived(data.bannerDismissed);
	const showBanner = $derived(data.banner.length > 0 && !bannerDismissed);

	const favicon = $derived(data.favicon);
	// Apple touch icons ignore prefers-color-scheme, so pick the light 180×180 master.
	const appleTouchIcon = $derived(favicon?.light.png.find((icon) => icon.size === 180) ?? null);
</script>

<svelte:head>
	<link rel="preconnect" href={PUBLIC_CMS_BASE_URL} />
	<link rel="preload" href={fontRegular} as="font" type="font/woff2" crossorigin="anonymous" />
	<link rel="preload" href={fontBold} as="font" type="font/woff2" crossorigin="anonymous" />
	{#if !IS_PROD}
		<meta name="robots" content="noindex, nofollow" />
	{/if}
	{#if favicon}
		{#if favicon.light.svg}
			<link
				rel="icon"
				type="image/svg+xml"
				media="(prefers-color-scheme: light)"
				href={favicon.light.svg}
			/>
		{/if}
		{#if favicon.dark.svg}
			<link
				rel="icon"
				type="image/svg+xml"
				media="(prefers-color-scheme: dark)"
				href={favicon.dark.svg}
			/>
		{/if}
		{#each favicon.light.png as icon (icon.size)}
			<link
				rel="icon"
				type="image/png"
				sizes="{icon.size}x{icon.size}"
				media="(prefers-color-scheme: light)"
				href={icon.url}
			/>
		{/each}
		{#each favicon.dark.png as icon (icon.size)}
			<link
				rel="icon"
				type="image/png"
				sizes="{icon.size}x{icon.size}"
				media="(prefers-color-scheme: dark)"
				href={icon.url}
			/>
		{/each}
		{#if appleTouchIcon}
			<link rel="apple-touch-icon" sizes="180x180" href={appleTouchIcon.url} />
		{/if}
	{/if}
</svelte:head>

<JsonLd schemas={data.schemas} />

<!-- Bypass block (WCAG 2.4.1). -->
<a
	href="#main-content"
	class="sr-only text-body-2 font-bold focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-full focus:bg-blue focus:px-5 focus:py-3 focus:text-white focus:outline-2 focus:outline-offset-2 focus:outline-blue"
>
	Aller au contenu principal
</a>

{#if data.header}
	<SiteHeader header={data.header} />
{/if}

<!-- `overflow-x-clip`: entrance animations park elements past the viewport edge; `clip`, unlike `hidden`, is not a scroll container, so sticky inside keeps working. -->
<main
	id="main-content"
	tabindex="-1"
	class="pt-27 space-y-18 pb-18 focus:outline-none overflow-x-clip"
>
	{@render children?.()}
</main>

{#if data.footer}
	<SiteFooter footer={data.footer} />
{/if}

{#if showBanner}
	<SiteMarquee announcements={data.banner} onclose={() => (bannerDismissed = true)} />
{/if}

<CookieBanner
	text={data.cookies.text}
	privacyPolicyUrl={data.cookies.privacyPolicyUrl}
	raised={showBanner}
/>
