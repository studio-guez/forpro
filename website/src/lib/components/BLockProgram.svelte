<script lang="ts">
  import type {IBlockLinkProgram} from "$lib/interfaces/cmsApiResponse";

  export let data: IBlockLinkProgram;

  const tags = [
      "stand",
      "atelier",
      "jeu",
      "visite",
      "radio-live",
      "démo",
      "concert",
      "apéro",
      "expo",
  ]

  const spaces = [
      "Village ForPro",
      "LearningLab",
      "MakerLab",
      "FoodLab",
      "GrandLab",
  ]

  const spacesColors = {
      "Village ForPro": "rgb(23, 84, 255)",
      "LearningLab": "rgb(255, 0, 252)",
      "MakerLab": "rgb(120, 210, 0)",
      "FoodLab": "#bea5e6",
      "GrandLab": "rgb(0, 145, 133)",
  } as {[key: string]: string}

  let timeFilterStatus: null | 'byDate' | 'continue' = null

  let activatedTags: string[] = []

  function toggleTag(tag: string) {

      activatedSpaces = []

      activatedTags = activatedTags.includes(tag)
          ? activatedTags.filter(value => value !== tag)
          // : [...activatedTags, tag]
          : [tag]
  }

  let activatedSpaces: string[] = []

  function toggleSpaces(space: string) {

      activatedTags = []

      activatedSpaces = activatedSpaces.includes(space)
          ? activatedTags.filter(value => value !== space)
          // : [...activatedTags, tag]
          : [space]
  }

  $: program_list_filteredByTag_and_filteredBySpace =  data.content.program_list.filter(value => {

      if(activatedTags.length === 0) return true

      if(value.program_list_tags.includes(activatedTags.join(','))) return true

  }).filter(value => {
      if(activatedSpaces.length === 0) return true

      if(value.program_list_space.includes(activatedSpaces.join(','))) return true
  }).filter(value => {
      if(timeFilterStatus === null) return true

      if(timeFilterStatus === 'continue') return value.program_list_heure === 'En continu'

      return value.program_list_heure !== 'En continu'
  })



</script>

<div class="block-program">

  <div class="block-program__title">
    <h2>
      {data.content.program_title}
    </h2>
  </div>

  <div class="block-program__spaces">
    {#each spaces as space}
      <button class="block-program__spaces__item"
           style="--space-color: {spacesColors[space]}"
           on:click={() => toggleSpaces(space)}
      >
        {space}
        {#if activatedSpaces.includes(space)}
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
        {/if}
      </button>
    {/each}
  </div>

  <div class="block-program__tags">
    {#each tags as tag}
      <button class="block-program__tags__item"
              class:is-active="{activatedTags.includes(tag)}"
              on:click={() => toggleTag(tag)}
      >
        {tag}
        {#if activatedTags.includes(tag)}
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
        {/if}
      </button>
    {/each}
  </div>

  <div class="block-program__content">
    {#each program_list_filteredByTag_and_filteredBySpace as event}
      <section class="block-program__content__item"
               data-space="{event.program_list_space}"
               style="--space-color: {spacesColors[event.program_list_space]}"
      >

        <h6 class="block-program__content__item__hour">
          {event.program_list_heure}
        </h6>
        <h4 class="block-program__content__item__header__title">
          {event.program_list_title}
        </h4>

        <h5 class="block-program__content__item__header__content">
          {@html event.program_list_content}
        </h5>

        {#if event.program_list_cta_url}
          <a class="block-program__content__item__cta"
             href="{event.program_list_cta_url}"
          >
            {event.program_list_cta_text}
          </a>
        {/if}

        <div class="block-program__content__item__tags-wrap">
          {#each event.program_list_tags.split(',') as tag}
            <div class="block-program__content__item__tags-wrap__tag">
              {tag}
            </div>
          {/each}
        </div>
      </section>
    {/each}
  </div>

  <div class="block-program__filter-time">
    <div class="block-program__filter-time__container"
         class:is-active="{timeFilterStatus !== null}"
    >
        <button class="block-program__filter-time__container__tag"
                on:click={() => timeFilterStatus = 'byDate'}
                class:is-active={timeFilterStatus === 'byDate'}
        >par heure</button>
        <button class="block-program__filter-time__container__tag"
                on:click={() => timeFilterStatus = 'continue'}
                class:is-active={timeFilterStatus === 'continue'}
        >en continu</button>
      {#if timeFilterStatus !== null}
        <button class="block-program__filter-time__container__tag" on:click={() => timeFilterStatus = null} style="padding-bottom: 0;">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
        </button>
      {/if}
    </div>
  </div>


</div>

<style lang="scss">
  @use '../../style/_scss-params';

  .block-program__title {
    text-align: center;
  }

  .block-program__spaces {
    display: flex;
    gap: .5rem;
    flex-wrap: wrap;
    justify-content: center;
    padding-top: 2rem;
    max-width: 40rem;
    margin-left: auto;
    margin-right: auto;

    @media (max-width: scss-params.$fp-breakpoint-sm) {
      justify-content: flex-start;
    }
  }

  .block-program__spaces__item {
    all: unset;
    background: white;
    color: var(--space-color);
    border-radius: 1rem;
    white-space: nowrap;
    padding: .05em .5em .25em;
    border: solid 2px var(--space-color);
    font-size: .85rem;
    user-select: none;
    cursor: pointer;

    &.is-active {
      color: var(--app-color--blue);
      background: white;
    }

    svg {
      display: inline;
      fill: var(--space-color);
      height: 1em;
      width: auto;
      vertical-align: middle;
      line-height: 1em;
    }
  }

  .block-program__tags {
    display: flex;
    gap: .5rem;
    flex-wrap: wrap;
    justify-content: center;
    padding-top: 2rem;
    max-width: 40rem;
    margin-left: auto;
    margin-right: auto;

    @media (max-width: scss-params.$fp-breakpoint-sm) {
      justify-content: flex-start;
    }
  }

  .block-program__tags__item {
    all: unset;
    background: rgb(237 237 237);
    color: #4b4b4b;
    border-radius: 1rem;
    white-space: nowrap;
    padding: .05em 1em .25em;
    border: solid 2px var(--s-evenements__tags-bg);
    font-size: .75rem;
    user-select: none;
    cursor: pointer;

    svg {
      display: inline;
      fill: var(--app-color--blue);
      height: 1em;
      width: auto;
      vertical-align: middle;
      line-height: 1em;
    }

    &.is-active {
      color: var(--app-color--blue);
      background: white;
      box-shadow: inset 0 0 0 2px var(--app-color--blue);
    }

    @media (max-width: scss-params.$fp-breakpoint-sm) {
      font-size: .65rem;
    }
  }

  .block-program__content {
    width: 100%;
    padding-top: 3rem;
    display: flex;
    column-gap: 1rem;
    row-gap: 3rem;
    flex-wrap: wrap;
    justify-content: center;

    @media (max-width: scss-params.$fp-breakpoint-sm) {
      //grid-template-columns: repeat(1, minmax(0, 1fr));
      row-gap: 2rem;
    }
  }

  .block-program__content__item {
    width: 100%;
    max-width: 19rem;
  }

  .block-program__content__item__hour {
    font-size: .75rem;
    line-height: 1em;
    font-weight: 500;
    color: var(--space-color);
  }

  .block-program__content__item__tags-wrap {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .25rem;
    padding-top: .5rem;
  }

  .block-program__content__item__header__title {
    font-size: 1rem;
    line-height: 1em;
    color: var(--space-color);
    margin-top: .25rem;
  }

  .block-program__content__item__header__content {
    line-height: 1em;
    font-weight: 500;
    font-style: italic;
    margin-top: .25rem;
    font-size: .85rem;

    :global(p) {
      margin-top: .25rem;
    }

    @media (max-width: scss-params.$fp-breakpoint-sm) {
      font-size: .75rem;
    }
  }

  .block-program__content__item__tags-wrap__tag {
    background: var(--space-color);
    color: white;
    border-radius: 1rem;
    white-space: nowrap;
    padding: .4em 1em .6em;
    border: solid 2px var(--s-evenements__tags-bg);
    font-size: .6rem;
    line-height: 1ex;
    user-select: none;
  }

  .block-program__content__item__cta {
    display: inline-block;
    //border: solid 2px var(--space-color);
    border-bottom: solid 2px var(--space-color);
    line-height: 1em;
    padding-bottom: .15rem;
    color: var(--space-color);
    //padding: .4em 1em .6em;
    //line-height: 1ex;
    //border-radius: 1rem;
    margin-top: .25rem;
  }

  .block-program__filter-time {
    position: sticky;
    bottom: .5rem;
    left: 0;
    display: flex;
    justify-content: center;
    margin-top: 2rem;
  }

  .block-program__filter-time__container {
    display: flex;
    align-items: center;
    z-index: 10;
    background: white;
    box-shadow:
            0 15px 30px rgba(0, 0, 0, .2),
            0 5px 5px rgba(0, 0, 0, .1);
    line-height: 1ex;
    padding: .25rem;
    border-radius: 1rem;
    gap: .25rem;
  }

  .block-program__filter-time__container__tag {
    user-select: none;
    background: white;
    color: rgba(0, 0, 0, .35);
    padding: .1em .5em .4em;
    border-radius: 1rem;
    line-height: 1em;
    display: flex;
    align-items: center;

    &.is-active {
      background: var(--app-color--green);
      color: var(--app-color--blue);
    }

    svg {
      display: block;
      fill: black;
      //background: rgba(0, 0, 0, .1);
      border-radius: 100%;
    }
  }
</style>
