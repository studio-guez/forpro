<main class="s-page"
>
    <div class="s-page__hero"
         style="background-color: {data.options.hero.backgroundcolor}">
        <h1 class="s-page__hero__title"
            style="color: {data.options.hero.textcolor}">{data.options.hero.text}</h1>
    </div>

    {#if data.options.showNewsletter}
        <div class="s-page__newsletter-box">
            <AppNewsletterSignup />
        </div>
    {/if}

    <main>{data.body}</main>
</main>


<script lang="ts">
    import {type IPage} from "$lib/interfaces/cmsApiResponse";
    import AppNewsletterSignup from "$lib/components/AppNewsletterSignup.svelte";

    export let data: IPage;

</script>

<style lang="scss">
  .s-page {
    min-height: calc( 100vh - var(--app-nav_height) );
  }

  .s-page__hero {
    overflow: hidden;
    box-sizing: border-box;
    width: 100%;
    padding: 2rem 1rem;
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

</style>
