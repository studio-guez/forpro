<section class="block-agenda">

  <h3 class="block-agenda__title">Prochains événements ForPro</h3>

  {#if eventPage}
    <div class="block-agenda__wrapper">
      {#each eventPage.childrenDetails.filter(event => event.pageContent.content.show_in_preview === "true").slice(0, 3) as childrenDetail}
        <div class="block-agenda__item">
          <AppEventTile
                  event="{childrenDetail}"
          />
        </div>
      {/each}
    </div>
  {/if}

  <div class="s-block-cta app-flex app-flex--justify_center block-agenda__link-to-events">
    <a href="/evenements"
       class="app-button app-button--rounded app-button--xl"
       style="
          --app-button--color: white;
          --app-button--background-color: var(--app-color--blue);
      "
    >
      Plus d'événements
    </a>
  </div>

</section>

<script lang="ts">
    import AppEventTile from "$lib/components/AppEventTile.svelte";

    import {onMount, tick} from "svelte";
    import {variables} from "$lib/utils/constants";
    import type {IPageEvents} from "$lib/interfaces/cmsApiResponse";

    let eventPage: IPageEvents | null = null;

onMount(() => {
    tick().then(async () => {
        try {
            const response = await fetch(`${variables.CMS_BASE_URL}/evenements.json?filter=upcoming`);

            if (!response.ok) {
                throw new Error('Failed to fetch booking content');
            }

            eventPage = await response.json();
        } catch (error) {
            console.error('Error fetching agenda content:', error);
            throw error;
        }
    })
})

</script>


<style lang="scss">
  .block-agenda {
    display: flex;
    justify-content: center;
    width: 100%;
    flex-direction: column;
  }

  .block-agenda__title {
    text-align: center;
    margin-bottom: 2rem;
    max-width: 40rem;
    margin-left: auto;
    margin-right: auto;
  }

  .block-agenda__wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: var(--app-gutter_regular);
    justify-content: center;
    box-sizing: border-box;
    width: 100%;
    align-items: stretch;
  }

  .block-agenda__item {
    position: relative;
    width: calc( (100% + var(--app-gutter_regular)) / 3 - var(--app-gutter_regular));
    min-width: 20rem;
    box-sizing: border-box;

    @media (max-width: 960px) {
      width: 100%;
      min-width: initial;
    }
  }

  .block-agenda__link-to-events {
    margin-top: 2rem;
    width: 100%;
  }
</style>
