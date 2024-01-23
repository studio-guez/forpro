<div class="s-layout"
>
  <div class="s-layout__nav-box">
    <AppNav/>
  </div>

  <div class="s-layout__main"
  >
    <slot/>
  </div>


  <div class="s-layout__footer-box"
  >
    <AppFooter/>
  </div>
</div>

<script lang="ts">
    import AppNav from "$lib/components/AppNav.svelte";
    import AppFooter from "$lib/components/AppFooter.svelte";
</script>

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
