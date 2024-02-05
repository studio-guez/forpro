<div class="s-page-home"

>
    <section class="s-page-home__header app-flex app-flex--column app-flex--justify_center app-flex--align_center">
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
  height: 80vh;
}

.s-page-home__header__logo {
  display: block;
  width: 75%;
}
</style>
