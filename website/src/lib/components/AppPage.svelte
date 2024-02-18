<main class="s-page"
>
    {#if (data.options.hero && data.options.hero.text)}
        <div class="s-page__hero"
             class:first-child-is-animation={Object.values(data.body)[0]?.content?.type === 'animated-list'}
             style="background-color: {data.options.hero.backgroundcolor}">
            <h1 class="s-page__hero__title"
                style="color: {data.options.hero.textcolor}">{data.options.hero.text}</h1>
        </div>
    {/if}

    <div class="s-page__content app-flex app-flex--justify_center"
         style="row-gap: 8rem"
    >
        {#each Object.keys(data.body) as section}
            {@const content = data.body[section].content}
            {@const image = data.body[section].image}

            {#if content.type === 'cta'}
                <div class="app-flex__basis-20-24">
                    <BlockCta content="{content}" image="{image}"/>
                </div>

            {:else if content.type === 'quote'}
                <div class="app-flex__basis-20-24">
                    <BlockQuote content="{content}" />
                </div>

            {:else if content.type === 'capsules'}
                <div class="app-flex__basis-20-24">
                    <BlockCapsules content="{content}" />
                </div>

            {:else if content.type === 'cards'}
                <div class="app-flex__basis-20-24">
                    <BlockCards content="{content}" />
                </div>

            {:else if content.type === 'profiles'}
                <div class="app-flex__basis-20-24">
                    <BlockProfiles content="{content}" />
                </div>

            {:else if content.type === 'list'}
                <div class="app-flex__basis-20-24">
                    <BlockList content="{content}" />
                </div>

            {:else if content.type === 'dropdown'}
                <div class="app-flex__basis-20-24">
                    <BlockDropdown content="{content}" />
                </div>

            {:else if content.type === 'cards-focus'}
                <div class="app-flex__basis-24-24">
                    <BlockCardsFocus content="{content}" />
                </div>

            {:else if content.type === 'body'}
                <div class="app-flex__basis-20-24">
                    <BlockHTMLContent content="{content}" />
                </div>

            {:else if (content.type === 'map' && content.content.style === "style1")}
                <div class="app-flex__basis-20-24">
                    <BlockSpaceBuilding content="{content}" />
                </div>
            {:else if (content.type === 'map')}
                <div class="app-flex__basis-20-24">
                    <BlockSpaceBuildingWithDetails content="{content}" />
                </div>

            {:else if content.type === 'google-maps'}
                <div class="app-flex__basis-20-24">
                    <BlockGoogleMaps content="{content}" />
                </div>

            {:else if content.type === 'animated-list'}
                <div class="app-flex__basis-20-24">
                    <BlockAnimatedList/>
                </div>
            {/if}

        {/each}
    </div>

    {#if data.options.showNewsletter}
        <div class="s-page__newsletter-box">
            <AppNewsletterSignup />
        </div>
    {/if}

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
    import BlockAnimatedList from "$lib/components/BlockAnimatedList.svelte";
    import BlockSpaceBuilding from "$lib/components/BlockSpaceBuilding.svelte";
    import BlockGoogleMaps from "$lib/components/BlockGoogleMaps.svelte";
    import BlockSpaceBuildingWithDetails from "$lib/components/BlockSpaceBuildingWithDetails.svelte";

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
    padding: 4rem 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;

    &.first-child-is-animation {
      padding-top: 0;
      padding-bottom: 1rem;
      margin-bottom: -2rem;
    }
  }

</style>
