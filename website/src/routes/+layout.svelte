<AppSeo seo="{$page.data?.seo ?? null}" siteTitle="{data?.title ?? null}"/>

<svelte:head>
  {#if trackWithMatomo}
  <!-- Matomo -->
  <script>
      var _paq = window._paq = window._paq || [];
      /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
          var u="//matomo.for-pro.ch/";
          _paq.push(['setTrackerUrl', u+'matomo.php']);
          _paq.push(['setSiteId', '1']);
          var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
          g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
      })();
  </script>
  <noscript><p><img referrerpolicy="no-referrer-when-downgrade" src="//matomo.for-pro.ch/matomo.php?idsite=1&amp;rec=1" style="border:0;" alt="" /></p></noscript>
  <!-- End Matomo Code -->
  {/if}
</svelte:head>


<script lang="ts">
    import "../app.css"
    import {menuIsOpen, modaleIsOpen, showCookieConsent, siteInfo} from "../store";
    import AppNav from "$lib/components/AppNav.svelte";
    import AppFooter from "$lib/components/AppFooter.svelte";
    import AppSeo from "$lib/components/AppSeo.svelte";
    import type {ISiteInfo} from "$lib/interfaces/cmsApiResponse";
    import {page} from '$app/stores';
    import {afterNavigate, beforeNavigate} from "$app/navigation";
    import AppModal from "$lib/components/AppModal.svelte";
    import {onMount} from "svelte";
    import AppCookieConsent from "$lib/components/AppCookieConsent.svelte";

    export let data: ISiteInfo;
    declare var _paq: unknown

    siteInfo.set(data)

    // Default to true so unsaved pages and pages without the option keep
    // their Matomo tracking. Tracking is only disabled when explicitly set
    // to false on a page.
    $: trackWithMatomo = $page.data?.options?.trackWithMatomo ?? true

    onMount(() => {
      if(  Number($page.url.searchParams.get('m')) === 1 ) modaleIsOpen.set(true)
    })

    beforeNavigate((navigation) => {
      menuIsOpen.set(false)

      document.querySelectorAll('.s-layout').forEach(value => {
        value.scrollTo({
          top: 0,
          behavior: 'smooth',
        })
      })
    })

    afterNavigate((navigation) => {
        document.querySelectorAll('.s-layout').forEach(value => {
            value.scrollTo({
                top: 0,
                behavior: 'smooth',
            })
        })

        if (trackWithMatomo && _paq) {
            _paq.push(['setCustomUrl', '/' + window.location.href])
            _paq.push(['setDocumentTitle', window.location.pathname])
            _paq.push(['setReferrerUrl', navigation.from])
            _paq.push(['trackPageView'])
        }
    })

</script>

<div class="s-layout"
     class:menu-is-open="{$menuIsOpen}"
>
  {#if ($modaleIsOpen)}
    <div class="s-layout__modal-box">
      <AppModal/>
    </div>
  {/if}

  <div class="s-layout__nav-box">
    <AppNav/>
  </div>

  {#key $page.params.slug}
  <div class="s-layout__main"
  >
    <slot />
  </div>
  {/key}

  {#if $showCookieConsent}
    <div class="s-layout__cookie-consent-box"
    >
      <AppCookieConsent/>
    </div>
  {/if}

  <div class="s-layout__footer-box"
  >
    <AppFooter/>
  </div>
</div>

