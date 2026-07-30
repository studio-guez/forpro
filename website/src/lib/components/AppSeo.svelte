<svelte:head>
    <title>{title}</title>

    {#if seo}
        <meta name="description" content="{seo.description}">
        <meta name="robots" content="{seo.robots}">
        <link rel="canonical" href="{seo.canonicalUrl}">

        <meta property="og:title" content="{seo.ogTitle}">
        <meta property="og:description" content="{seo.ogDescription}">
        <meta property="og:type" content="{seo.ogType}">
        <meta property="og:url" content="{seo.canonicalUrl}">
        <meta property="og:locale" content="{seo.locale}">
        {#if seo.ogSiteName}
            <meta property="og:site_name" content="{seo.ogSiteName}">
        {/if}
        {#if seo.ogImage}
            <meta property="og:image" content="{seo.ogImage}">
        {/if}

        <meta name="twitter:card" content="{seo.twitterCardType}">
        <meta name="twitter:title" content="{seo.ogTitle}">
        <meta name="twitter:description" content="{seo.ogDescription}">
        {#if seo.ogImage}
            <meta name="twitter:image" content="{seo.ogImage}">
        {/if}
        {#if seo.twitterSite}
            <meta name="twitter:site" content="@{seo.twitterSite}">
        {/if}
        {#if seo.twitterCreator}
            <meta name="twitter:creator" content="@{seo.twitterCreator}">
        {/if}
    {/if}
</svelte:head>

<script lang="ts">
    import type {ISeo} from "$lib/interfaces/cmsApiResponse";

    /** Metadata resolved by the CMS. Null on routes that are not CMS-driven. */
    export let seo: ISeo | null = null;
    /** Site title from the CMS, used when a route has no metadata of its own. */
    export let siteTitle: string | null = null;
    /** Last resort, so no page is ever rendered without a title. */
    export let fallbackTitle: string = "Fondation ForPro";

    $: title = seo?.title || siteTitle || fallbackTitle;
</script>
