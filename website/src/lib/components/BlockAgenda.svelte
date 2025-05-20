<section class="block-agenda">
  {#if eventPage}
    {#each eventPage.childrenDetails as childrenDetail}
      <div class="block-agenda__item">
        <AppEventTile
                event="{childrenDetail}"
        />
      </div>
    {/each}
  {/if}
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
</style>
