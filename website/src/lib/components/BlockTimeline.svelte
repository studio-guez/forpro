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
         role="button"
         tabindex="0"
         on:click={onClickCopyButton}
         on:keydown={(e) => (e.key === 'Enter' || e.key === ' ') && onClickCopyButton()}>{textButtonShareLink}
    </div>
  </div>

</section>

