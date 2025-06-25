<script lang="ts">
import {formatDate} from "$lib/utils/formatDate.js";
import type {IChildrenDetils__event} from "$lib/interfaces/cmsApiResponse.js";
import {formatTime} from "$lib/utils/formatTime";

export let event: IChildrenDetils__event;

export let isArchive = false;

</script>

<div class="app-event-tile"
     class:app-event-tile--is-archive="{isArchive}"
>
  <div class="app-event-tile__title">
    {event.pageContent.content.title}
  </div>
  {#if event.pageContent.content.withpartner === 'true'}
    <div class="app-event-tile__partner">
      partenaires
    </div>
  {/if}
  {#if !isArchive}
    <div class="app-event-tile__cover">
      {#if event.cover[0]}
        <img class="app-event-tile__cover__image"
             alt="cover"
             src="{event.cover[0]?.resize.reg}"
        />
      {:else}
        <img class="app-event-tile__cover__image"
             alt="cover"
             src="empty_images/240625_intro-outro_ForPro_Admin-3.jpg"
        />
      {/if}
    </div>
  {/if}
  <div class="app-event-tile__tags">
    <div class="app-event-tile__tags__item">
      {@html formatDate(event.pageContent.content.datestart)}
      {#if !isArchive}
        <div>{formatTime(event.pageContent.content.hourstart)}</div>
      {/if}
    </div>
  </div>

  <div class="app-event-tile__description">
    <div>
      {event.pageContent.content.description}
    </div>
  </div>

  <div class="app-event-tile__details">
    <a class="app-button app-button--rounded"
       href="{event.pageContent.uri}"
    >
      {#if event.pageContent.content.parent_page_link_text && event.pageContent.content.parent_page_link_text.length > 0}
        {event.pageContent.content.parent_page_link_text}
      {:else }
        En savoir plus
      {/if}
    </a>
  </div>
</div>


<style lang="scss">
  @use "../../../src/style/_scss-params";


  .app-event-tile {
    text-align: center;
    display: block;
    width: 100%;
    height: calc(100% - 2rem);
    margin-top: 2rem;
    box-sizing: border-box;
    padding: 2rem 1rem;
    border-radius: 1rem;
    position: relative;
    background: var(--app-color-beige);
    //background: var(--app-color--blue--light);
    border: solid 2px var(--app-color--blue);

    @media (max-width: 960px) {
      width: 100%;
      min-width: initial;
    }

    &.app-event-tile--is-archive {
      width: 100%;
      display: flex;
      padding: .5rem;
      border-radius: 0;
      border: none;
      border-top: solid 2px;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      background: transparent;
      margin-top: 0;

      &:last-child {
        border-bottom: 2px solid;
      }
    }
  }

  .app-event-tile__title {
    font-size: 1.25rem;
    line-height: 1em;
    font-weight: 900;
    color: white;
    background: var(--fp-color-makerlab);
    padding: var(--app-gutter_regular);
    top: 0;
    left: 0;
    width: 100%;
    box-sizing: border-box;
    border-radius: 2rem;
    transform: translate(0, -4rem);
    margin-bottom: -2rem;
    max-width: 34rem;
    flex-shrink: 0;
    margin-right: 100%;

    .app-event-tile--is-archive & {
      transform: translate(0, 0);
      background: none;
      text-align: left;
      margin-bottom: 0;
      color: black;
      font-size: 1.25rem;
      padding: 0;
    }

    @media (max-width: scss-params.$fp-breakpoint-xs) {
      font-size: 1rem;
    }
  }

  .app-event-tile__partner {
    position: absolute;
    top: 0;
    right: 0;
    background: var(--app-color--green);
    transform: translate(40%, 0) rotate(25deg);
    padding: .15em .5em .35em;
    font-size: .65rem;
    border-radius: 2em;
    color: var(--app);
  }

  .app-event-tile__cover {
    position: relative;

    .app-event-tile--is-archive & {
      margin-bottom: 0;
    }
  }

  .app-event-tile__cover__image {
    display: block;
    width: 100%;
    aspect-ratio: 5/3;
    object-fit: cover;
    border-radius: 1rem;

    &.app-event-tile__cover--default {
      background: var(--app-color--blue);
      display: flex;
      justify-content: center;
      align-items: center;

      > div {
        font-size: 3rem;
        flex-wrap: nowrap;
        font-weight: 900;
        line-height: 1em;
        transform: rotate(-5deg);
      }
    }

    .app-event-tile--is-archive & {
      display: none;
    }
  }

  .app-event-tile__tags {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .5rem;
    padding: .5rem 0;
    order: 3;

    .app-event-tile--is-archive & {
      order: initial;
    }
  }

  .app-event-tile__tags__item {
    display: block;
    background: var(--app-color--blue);
    color: white;
    padding: .4em 1.5em .6em;
    border-radius: 4em;
    font-weight: 800;
    font-size: 1rem;
    line-height: 1.25em;
    &:first-letter {
      text-transform: uppercase;
    }

    > div {
      font-weight: 400;
    }

    .app-event-tile--is-archive & {
      font-size: inherit;
      font-weight: inherit;
    }
  }

  .app-event-tile__description {
    font-weight: 500;
    font-size: 1rem;
    line-height: 1.15em;
    padding-bottom: 1rem;
    text-align: left;

    .app-event-tile--is-archive & {
      display: none;
    }
  }
</style>
