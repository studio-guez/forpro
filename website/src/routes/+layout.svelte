<script lang="ts">
	import '../app.css';
	import 'lenis/dist/lenis.css';
	import { onMount } from 'svelte';
	import { initSmoothScroll } from '$lib/utils/smoothScroll';
	import SiteHeader from '$lib/components/layout/SiteHeader.svelte';
	import SiteMarquee from '$lib/components/layout/SiteMarquee.svelte';
	import SiteFooter from '$lib/components/layout/SiteFooter.svelte';
	import CookieBanner from '$lib/components/layout/CookieBanner.svelte';
	import { IS_PROD } from '$lib/env';
	import type { LayoutData } from './$types';

	interface Props {
		data: LayoutData;
		children?: import('svelte').Snippet;
	}

	let { data, children }: Props = $props();

	onMount(initSmoothScroll);

	const favicon = $derived(data.favicon);
	// Apple touch icons ignore prefers-color-scheme, so pick the light 180×180 master.
	const appleTouchIcon = $derived(favicon?.light.png.find((icon) => icon.size === 180) ?? null);
</script>

<svelte:head>
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

<!-- Bypass block (WCAG 2.4.1): the header carries the whole navigation, so keyboard and
	 screen reader users get a first tab stop that jumps straight past it. -->
<a
	href="#main-content"
	class="sr-only text-body-2 font-bold focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-full focus:bg-blue focus:px-5 focus:py-3 focus:text-white focus:outline-2 focus:outline-offset-2 focus:outline-blue"
>
	Aller au contenu principal
</a>

{#if data.header}
	<SiteHeader header={data.header} />
{/if}

<main
	id="main-content"
	tabindex="-1"
	class="pt-27 space-y-9 lg:space-y-18 pb-18 focus:outline-none"
>
	{@render children?.()}
</main>

{#if data.footer}
	<SiteFooter footer={data.footer} />
{/if}

{#if data.banner.length > 0}
	<SiteMarquee announcements={data.banner} />
{/if}

<CookieBanner
	text={data.cookies.text}
	privacyPolicyUrl={data.cookies.privacyPolicyUrl}
	raised={data.banner.length > 0}
/>
