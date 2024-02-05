<main class="s-page"
>
    {#if (data.options.hero && data.options.hero.text)}
        <div class="s-page__hero"
             style="background-color: {data.options.hero.backgroundcolor}">
            <h1 class="s-page__hero__title"
                style="color: {data.options.hero.textcolor}">{data.options.hero.text}</h1>
        </div>
    {/if}

    {#if data.options.showNewsletter}
        <div class="s-page__newsletter-box">
            <AppNewsletterSignup />
        </div>
    {/if}

    <div class="app-flex app-flex--justify_center"
    >
        <div class="s-page__content app-flex__basis-20-24"

        >
            {#each Object.keys(data.body) as section}
                {@const content = data.body[section].content}
                {@const image = data.body[section].image}

                {#if content.type === 'cta'}
                    <BlockCta content="{content}" image="{image}"/>

                {:else if content.type === 'quote'}
                    <BlockQuote content="{content}" />

                {:else if content.type === 'capsules'}
                    <BlockCapsules content="{content}" />

                {:else if content.type === 'cards'}
                    <BlockCards content="{content}" />

                {:else if content.type === 'profiles'}
                    <BlockProfiles content="{content}" />

                {:else if content.type === 'list'}
                    <BlockList content="{content}" />

                {:else if content.type === 'dropdown'}
                    <BlockDropdown content="{content}" />

                {:else if content.type === 'cards-focus'}
                    <BlockCardsFocus content="{content}" />

                {:else if content.type === 'body'}
                    <BlockHTMLContent content="{content}" />

                {:else if content.type === 'animated-list'}
                    {#if browser}
                        <LottiePlayer
                                src="/lottie/desktop-model-jeune_lottie.json"
                                autoplay="{true}"
                        />
                    {/if}
                {/if}

            {/each}
        </div>
    </div>

</main>


<script lang="ts">
    import {type IPage} from "$lib/interfaces/cmsApiResponse";
    import AppNewsletterSignup from "$lib/components/AppNewsletterSignup.svelte";
    import BlockCta from "$lib/components/BlockCta.svelte";
    import BlockQuote from "$lib/components/BlockQuote.svelte";
    import BlockCapsules from "$lib/components/BlockCapsules.svelte";
    import BlockCards from "$lib/components/BlockCards.svelte";
    import BlockProfiles from "$lib/components/BlockProfiles.svelte";
    import BlockList from "$lib/components/BlockList.svelte";
    import BlockDropdown from "$lib/components/BlockDropdown.svelte";
    import BlockHTMLContent from "$lib/components/BlockHTMLContent.svelte";
    import BlockCardsFocus from "$lib/components/BlockCardsFocus.svelte";
    import {browser} from "$app/environment";
    import {LottiePlayer} from "@lottiefiles/svelte-lottie-player";

    export let data: IPage;

</script>

<style lang="scss">
  .s-page {
    min-height: calc( 100vh - var(--app-nav_height) );
  }

  .s-page__hero__title {
    font-weight: 600;
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
