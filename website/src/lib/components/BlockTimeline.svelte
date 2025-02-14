<script lang="ts">
  import { onMount } from 'svelte'
  import {copyTextToClipboard} from "$lib/utils/copyTextToClipboard";
  import type {IBlockTimeline, IBlockTimeline_item} from "$lib/interfaces/cmsApiResponse";
  import {formatDate} from "$lib/utils/formatDate";

  let timelineElement: HTMLElement | null = null

  export let timelineData: IBlockTimeline

  onMount(() => {
    setTimelineInteractionObserver(timelineElement)
  })

  function setTimelineInteractionObserver(timelineElement: HTMLElement | null) {
    if (timelineElement === null) return

    const timelineItemInteractionObserver = new IntersectionObserver(
      entries => interactionObserverCallback(entries),
      {}
    )

    timelineElement.querySelectorAll('.v-time-line__item').forEach(timelineItem => {
      timelineItemInteractionObserver.observe(timelineItem)
    })
  }

  function interactionObserverCallback(entries: IntersectionObserverEntry[]) {
    entries.forEach(entry => {
      if (!(entry.target instanceof HTMLElement)) return
      if (entry.isIntersecting) {
        entry.target.classList.add('ts-is-intersecting')
      } else {
        entry.target.classList.remove('ts-is-intersecting')
      }
    })
  }


  const url           = "https://for-pro.ch/recrutement/"
  const extButton_1   = 'Partagez cette page!'
  const extButton_2   = 'Lien copié avec succès. Collez où vous le souhaitez!'

  let textButtonShareLink = extButton_1

  function onClickCopyButton() {
      if(textButtonShareLink !== extButton_2) {
          copyTextToClipboard(url)
          textButtonShareLink = extButton_2
          window.setTimeout(() => { textButtonShareLink = extButton_1 }, 2_000)
      }
  }
</script>

<section class="v-time-line" bind:this={timelineElement}>
  <div class="v-time-line__wrap">
    {#each timelineData.content.items as timelineItem}
      <div class="v-time-line__item">
        {#if (timelineItem.datemessage)}
          <h6 class="v-time-line__item__date">
            {timelineItem.datemessage}
          </h6>
        {:else if timelineItem.date}
          <h6 class="v-time-line__item__date">
            {#if timelineItem.dateend}du{/if}
            {@html formatDate(timelineItem.date, true)}
            {#if timelineItem.dateend}
              au {@html formatDate(timelineItem.dateend, true)}
            {/if}
            {#if timelineItem.details}
                <br>{timelineItem.details}
            {/if}
          </h6>
        {/if}
        <h3 class="v-time-line__item__title">{timelineItem.title}</h3>

        <div class="v-time-line__item__desc app-remove-margin-child">
          {@html timelineItem.content}

          {#if (timelineItem.button_link)}
            <a class="app-button app-button--rounded" target="_blank"
               href="{timelineItem.button_link}"
               style="--app-button--color: var(--app-color--green);"
            >
              {#if (timelineItem.button_text)}
                {timelineItem.button_text}
              {:else}
                plus d'information
              {/if}
            </a>
          {/if}
        </div>
      </div>
    {/each}
  </div>

  <div class="v-time-line__button">
    <div class="app-button app-button--rounded"
         style="--app-button--color: var(--app-color--green);"
         on:click={onClickCopyButton}>{textButtonShareLink}
    </div>
  </div>

</section>

<style lang="scss">
  @use "../../style/_scss-params";

  .v-time-line {
    display: flex;
    align-items: center;
    gap: 5rem;
    padding-bottom: 5rem;
    flex-direction: column;
    width: 100%;
  }

  .v-time-line__wrap {
    position: relative;
    display: block;
    width: min(70rem, 100%);

    &:before {
      content: '';
      position: absolute;
      width: 4px;
      height: 100%;
      background: var(--app-color--blue);
      top: 0;
      left: 50%;
      transform: translate(-50%, 0);

      @media (max-width: scss-params.$fp-breakpoint-sm) {
        left: .25rem;
      }
    }

    .v-time-line__item {
      position: relative;
      width: 50%;
      box-sizing: border-box;
      padding-right: 2rem;
      margin-top: 0;
      padding-bottom: 2rem;

      &:before {
        content: "";
        position: absolute;
        display: block;
        width: 1.5rem;
        height: 1.5rem;
        box-sizing: border-box;
        border: solid 3px var(--app-color--blue);
        border-radius: 100%;
        right: 0;
        top: 0;
        transform: translate(50%, -25%);

        //transition with InteractionObserver (see css below)
        transition: background-color 1s ease-in-out;
        background-color: white;
      }

      &:first-child {
        margin-top: 0;

      }

      &:nth-child(2n) {
        margin-left: 50%;
        padding-right: 0;
        padding-left: 2rem;

        &:before {
          right: auto;
          left: 0;
          top: 0;
          transform: translate(-50%, -25%);
        }
      }

      @media (max-width: scss-params.$fp-breakpoint-sm) {
        width: 100%;
        margin-left: 0 !important;
        padding-left: 1rem !important;
        padding-right: 0 !important;

        &:before {
          left: .75rem !important;
          width: 1rem !important;
          height: 1rem !important;
          transform: translate(-100%, 0) !important;
          background-image: none !important;
        }
      }

      .v-time-line__item__date {
        font-size: .85rem;
        color: var(--app-color--blue);
        padding-bottom: .25rem;

        //transition with InteractionObserver (see css below)
        transition: opacity .75s ease-in-out, transform .55s ease-in-out;
        transform: translateY(2rem);
        opacity: 0;

        &::first-letter {
          text-transform: uppercase;
        }
      }

      .v-time-line__item__title {
        margin: 0;
        text-align: left;

        //transition with InteractionObserver (see css below)
        transition: opacity .75s ease-in-out, transform .65s ease-in-out;
        transform: translateY(2rem);
        opacity: 0;
      }

      .v-time-line__item__desc {
        margin: 0;
        text-align: left;
        padding-top: 1rem;

        //transition with InteractionObserver (see css below)
        transition: opacity .75s ease-in-out, transform .75s ease-in-out;
        transform: translateY(2rem);
        opacity: 0;
      }
      :global(.v-time-line__item__desc p) {
        margin-top: 1rem;
        margin-bottom: 1rem;
      }

      //&.ts-is-intersecting {
      &:before {
        background-color: #ffcb8f;
      }

      .v-time-line__item__date {
        opacity: 1;
        transform: translateY(0);
      }

      .v-time-line__item__title {
        opacity: 1;
        transform: translateY(0);
      }

      .v-time-line__item__desc {
        opacity: 1;
        transform: translateY(0);
      }
      //}

      &.v-time-line__item--gant:global(.ts-is-intersecting) {
        &:before {
          border-width: 4px;
          width:  2.5rem;
          height: 2.5rem;
          background-image: url('/icons/gant.svg');
          background-size: 66% 66%;
          background-repeat: no-repeat;
          background-position: center;
        }
      }
      &.v-time-line__item--pen:global(.ts-is-intersecting) {
        &:before {
          border-width: 4px;
          width:  2.5rem;
          height: 2.5rem;
          background-image: url('/icons/pen.svg');
          background-size: 66% 66%;
          background-repeat: no-repeat;
          background-position: center;
        }
      }
    }
  }

  .v-time-line__button {
    display: flex;
    justify-content: center;
    align-content: center;
  }

  h3 {
    color: var(--app-color--pink);
  }
</style>
