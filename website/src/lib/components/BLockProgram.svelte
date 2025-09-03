<script lang="ts">
  import type {IBlockLinkProgram} from "$lib/interfaces/cmsApiResponse";

  const tags = [
      "stand",
      "atelier",
      "atelier cuisine",
      "jeu",
      "visite",
      "radio-live",
      "démo",
      "conférence",
      "atelier collaboratif",
      "concert",
      "apéro",
      "restauration",
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

  export let data: IBlockLinkProgram;
</script>

<div class="block-program">

  <div class="block-program__title">
    <h2>
      {data.content.program_title}
    </h2>
  </div>

  <div class="block-program__spaces">
    {#each spaces as space}
      <div class="block-program__spaces__item"
           style="--space-color: {spacesColors[space]}"
      >
        {space}
      </div>
    {/each}
  </div>

  <div class="block-program__tags">
    {#each tags as tag}
      <button class="block-program__tags__item"
              on:click={() => console.log(tag)}
      >{tag}</button>
    {/each}
  </div>

  <div class="block-program__content">
    {#each data.content.program_list as event}
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
    //background: white;
    //color: var(--app-color--blue);
    border-radius: 1rem;
    white-space: nowrap;
    padding: .05em 1em .25em;
    border: solid 2px var(--s-evenements__tags-bg);
    font-size: .75rem;
    user-select: none;
    cursor: pointer;

    &.is-active {
      color: var(--app-color--blue);
      background: white;
    }

    @media (max-width: scss-params.$fp-breakpoint-sm) {
      font-size: .65rem;
    }
  }

  .block-program__content {
    padding-top: 3rem;
    display: flex;
    column-gap: 1rem;
    row-gap: 3rem;
    flex-wrap: wrap;
    justify-content: center;

    @media (max-width: scss-params.$fp-breakpoint-sm) {
      grid-template-columns: repeat(1, minmax(0, 1fr));
      row-gap: 2rem;
    }
  }

  .block-program__content__item {
    width: 100%;
    max-width: 20rem;
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
    font-size: 1rem;
    line-height: 1em;
    font-weight: 500;
    font-style: italic;
    margin-top: .25rem;

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
    cursor: pointer;
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
</style>
