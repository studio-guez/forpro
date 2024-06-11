<script lang="ts">
    import "../style/_main.scss"
    import {menuIsOpen, modaleIsOpen, showFooter, showNav, siteInfo} from "../store";
    import AppNav from "$lib/components/AppNav.svelte";
    import AppFooter from "$lib/components/AppFooter.svelte";
    import type {ISiteInfo} from "$lib/interfaces/cmsApiResponse";
    import {page} from '$app/stores';
    import {afterNavigate, beforeNavigate} from "$app/navigation";
    import AppModal from "$lib/components/AppModal.svelte";
    import {onMount} from "svelte";

    export let data: ISiteInfo;

    siteInfo.set(data)

    onMount(() => {
      if(  Number($page.url.searchParams.get('m')) === 1 ) modaleIsOpen.set(true)
      setNavAndFooterVisibility(window.location.pathname)
    })

    beforeNavigate((navigation) => {
      menuIsOpen.set(false)

      document.querySelectorAll('.s-layout').forEach(value => {
        value.scrollTo({
          top: 0,
          behavior: 'smooth',
        })
      })

        setNavAndFooterVisibility(navigation.to?.route.id || '')

    })

    afterNavigate(() => {
        document.querySelectorAll('.s-layout').forEach(value => {
            value.scrollTo({
                top: 0,
                behavior: 'smooth',
            })
        })
    })

    function setNavAndFooterVisibility(rootId: string) {
        if( rootId === '/changerderegard' ) {
            showNav.set(false)
            showFooter.set(false)
        } else {
            showNav.set(true)
            showFooter.set(true)
        }
    }


</script>

<div class="s-layout"
     class:menu-is-open="{$menuIsOpen}"
     class:has-no-nav="{!$showNav}"
>
  {#if ($modaleIsOpen)}
    <div class="s-layout__modal-box">
      <AppModal/>
    </div>
  {/if}

  {#if $showNav}
  <div class="s-layout__nav-box">
    <AppNav/>
  </div>
  {/if}

  {#key $page.params.slug}
  <div class="s-layout__main"
  >
    <slot />
  </div>
  {/key}

  {#if $showFooter}
  <div class="s-layout__footer-box"
  >
    <AppFooter/>
  </div>
  {/if}
</div>

<style lang="scss">
  .s-layout {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    position: relative;
    width: 100%;
    height: 100%;
    overflow: auto;
    scrollbar-gutter: stable;
    box-sizing: border-box;
    padding-top: var(--app-nav_height);

    &.menu-is-open {
      overflow: hidden;
    }

    &.has-no-nav {
      padding-top: 0;
    }
  }

  .s-layout__main {
    min-height: calc(100vh - 10rem);
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .s-layout__modal-box {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, .5);
    backdrop-filter: blur(10px);
    z-index: 100000;
  }

  .s-layout__nav-box {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    box-sizing: border-box;
    z-index: 1000;
  }

  .s-layout__footer-box {
    box-sizing: border-box;
    width: 100%;
  }
</style>

