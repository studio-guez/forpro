<div class="s-page-home"

>
    <section class="s-page-home__header app-flex app-flex--column app-flex--justify_center app-flex--align_center app-flex--nowrap">
        <img class="s-page-home__header__logo"
                src="/logo.svg" alt="logo">
        <img class="s-page-home__header__logo"
             src="/logo.svg" alt="logo">

    </section>
    <AppPage
            data="{data}"
    />
</div>


<script lang="ts">
    import {type IPage} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";

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
