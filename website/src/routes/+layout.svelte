<script lang="ts">
    import "../style/_main.scss"
    import {menuIsOpen, siteInfo} from "../store";
    import AppNav from "$lib/components/AppNav.svelte";
    import AppFooter from "$lib/components/AppFooter.svelte";
    import type {ISiteInfo} from "$lib/interfaces/cmsApiResponse";
    import {fly} from "svelte/transition"
    import { page } from '$app/stores';
    import {afterNavigate, beforeNavigate} from "$app/navigation";

    export let data: ISiteInfo;

    siteInfo.set(data)

    afterNavigate(() => {
      document.querySelector('.s-layout')?.scrollTo({top: 0, behavior: 'smooth'})
    })

</script>

<div class="s-layout"
     class:menu-is-open="{$menuIsOpen}"
>
  <div class="s-layout__nav-box">
    <AppNav/>
  </div>

  {#key $page?.params.slug}
  <div class="s-layout__main"
       in:fly={{x:-200, duration: 300, delay: 300 }} out:fly={{ duration: 300 }}
  >
    <slot/>
  </div>
  {/key}

  <div class="s-layout__footer-box"
  >
    <AppFooter/>
  </div>
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

