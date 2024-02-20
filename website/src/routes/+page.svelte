<div class="s-page-home"

>
    <section class="s-page-home__header app-flex app-flex--column app-flex--justify_center app-flex--align_center app-flex--nowrap">
        {#if browser}
        <LottiePlayer
                src="lottie/desktop_logo-header.json"
                autoplay="{true}"
                background="transparent"
                speed="1"
                style="width: 100%; height: 100%"
                direction="1"
                mode="normal"
                width="100%"
                height="100%"
                controls="{false}"
                controlsLayout="[]"
                renderer="svg"
        />
        {/if}
    </section>
    <AppPage
            data="{data}"
    />
</div>


<script lang="ts">
    import {type IPage} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";
    import {LottiePlayer} from "@lottiefiles/svelte-lottie-player";
    import {browser} from "$app/environment";

    export let data: IPage;

</script>

<style lang="scss">
.s-page-home__header {
  position: relative;
  height: max(80vh, 40vw);
}

.s-page-home__header__logo {
  display: block;
  width: 90%;

  &+& {
    margin-top: 2rem;
  }
}
</style>
