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
                <div class="app-flex__basis-20-24 app-flex app-flex--justify_center">
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

            {:else if content.type === 'video'}
                <div class="app-flex__basis-20-24">
                    <iframe title="youtube embed"
                            width="720"
                            height="405"
                            class:is-vertical={content.content.is_vertical === 'true'}
                            class="s-page__content__youtube"
                            src="{`https://www.youtube.com/embed/${content.content.url.match(/(?:youtu\.be\/|youtube\.com\/(?:.*v=|.*\/))([a-zA-Z0-9_-]{11})/)[1]}?modestbranding=1&playsinline=1&color=white`}"
                            frameborder="0"
                            allowfullscreen
                    />
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
                <div class="app-page-block-container app-page-block-container--body app-flex__basis-20-24 app-flex app-flex--justify_center">
                    <BlockHTMLContent content="{content}" />
                </div>

            {:else if content.type === 'logos-list'}
                <div class="app-page-block-container app-flex__basis-20-24 app-flex app-flex--justify_center">
                    <AppBlockLogos
                            data="{data.body[section]}"
                    />
                </div>

            {:else if content.type === 'graphic-list' }
                <div class="app-flex__basis-22-24 app-flex app-flex--justify_center">
                    <BlockListGraphic
                            data="{content}"
                    />
                </div>

            {:else if (content.type === 'map' && content.content.style === "style1")}
                <div class="app-flex__basis-20-24 app-flex app-flex--justify_center">
                    <BlockSpaceBuilding />
                </div>
            {:else if (content.type === 'map')}
                <div class="app-flex__basis-20-24 app-flex app-flex--justify_center">
                    <BlockSpaceBuildingWithDetails content="{content}" />
                </div>

            {:else if content.type === 'google-maps'}
                <div class="app-flex__basis-20-24">
                    <BlockGoogleMaps content="{content}" />
                </div>

            {:else if content.type === 'animated-list'}
                <div class="app-flex__basis-20-24">
                    <BlockAnimatedList
                            content="{content}"
                    />
                </div>
            {:else if content.type === 'image'}
                <div class="app-flex__basis-24-24 app-flex app-flex--justify_center">
                    <BlockImage
                            image="{image}"
                            content="{content}"
                    />
                </div>
            {:else if content.type === 'timeline' }
                <div class="app-flex__basis-22-24 app-flex app-flex--justify_center">
                    <BlockTimeline
                            timelineData="{content}"
                    />
                </div>
            {:else if content.type === 'listOfPDF' }
                <div class="app-flex__basis-20-24 app-flex app-flex--justify_center">
                    <BlockListOfLinks
                            blockListOfLinksData="{content}"
                    />
                </div>

            {:else if content.type === 'agenda' }
              <div class="app-flex__basis-20-24 app-flex app-flex--justify_center">
                <BlockAgenda/>
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
    import BlockImage from "$lib/components/BlockImage.svelte";
    import AppBlockLogos from "$lib/components/AppBlockLogos.svelte";
    import BlockTimeline from "$lib/components/BlockTimeline.svelte";
    import BlockListOfLinks from "$lib/components/BlockListOfLinks.svelte";
    import BlockListGraphic from "$lib/components/BlockListGraphic.svelte";
    import BlockAgenda from "$lib/components/BlockAgenda.svelte";

    export let data: IPage;
</script>

<style lang="scss">
  @use "../../style/_scss-params";

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

    @media (max-width: scss-params.$fp-breakpoint-xs) {
      padding-top: 1rem;
      padding-bottom: 3rem;
    }
  }

  .s-page__content {
    row-gap: 8rem;

    @media (max-width: scss-params.$fp-breakpoint-xs) {
      row-gap: 4rem;
    }
  }

  :global(.s-page-events-slug .s-page__content) {
    row-gap: 6rem;
  }

  .app-page-block-container--body {
    &:last-child {
      margin-bottom: 4rem;
    }
  }

  .s-page__content__youtube {
      width: 100%;
      display: block;
      height: auto;
      aspect-ratio: 720/405;
      margin: auto;
      max-width: 50rem;

      &.is-vertical {
          aspect-ratio: 1080/1920;

          max-width: 18rem;
      }
  }

</style>
