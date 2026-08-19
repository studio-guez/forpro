<script lang="ts">
	import '../app.css';
	import SiteHeader from '$lib/components/SiteHeader.svelte';
	import { IS_PREPROD } from '$lib/env';
	import type { LayoutData } from './$types';

	interface Props {
		data: LayoutData;
		children?: import('svelte').Snippet;
	}

	let { data, children }: Props = $props();

	const favicon = $derived(data.favicon);
	// Apple touch icons ignore prefers-color-scheme, so pick the light 180×180 master.
	const appleTouchIcon = $derived(favicon?.light.png.find((icon) => icon.size === 180) ?? null);
</script>

<svelte:head>
	{#if IS_PREPROD}
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

{#if data.header}
	<SiteHeader header={data.header} />
{/if}

<main class="max-w-360 mx-auto px-base pt-27 space-y-18">
	{@render children?.()}
</main>
