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

  let blockProgramElement: null | HTMLElement = null

  let timeFilterStatus: null | 'byDate' | 'continue' = null

  function setTimeFilterStatus(status: 'byDate' | 'continue' | null) {
      console.log(blockProgramElement)

      if (blockProgramElement) {
          blockProgramElement.scrollIntoView({behavior: "smooth", block: 'start'});
      }
      timeFilterStatus = status
  }

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

  $: usedTags = [...new Set(data.content.program_list.flatMap(v => v.program_list_tags ? v.program_list_tags.split(',').map(t => t.trim()).filter(Boolean) : []))]
  $: usedSpaces = [...new Set(data.content.program_list.map(v => v.program_list_space).filter(Boolean))]
  $: hasMultipleTags = usedTags.length > 1
  $: hasMultipleSpaces = usedSpaces.length > 1
  $: hasMultipleTimeCategories = data.content.program_list.some(v => v.program_list_heure === 'En continu') && data.content.program_list.some(v => v.program_list_heure !== 'En continu')

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

<div class="block-program"
     bind:this={blockProgramElement}
>

  <div class="block-program__title">
    <h2>
      {data.content.program_title}
    </h2>
  </div>

  {#if hasMultipleSpaces}
  <div class="block-program__spaces">
    {#each usedSpaces as space}
      <button class="block-program__spaces__item"
           style="--space-color: {spacesColors[space]}"
           class:is-active={activatedSpaces.includes(space)}
           on:click={() => toggleSpaces(space)}
      >
        {space}
        {#if activatedSpaces.includes(space)}
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
        {/if}
      </button>
    {/each}
  </div>
  {/if}

  {#if hasMultipleTags}
  <div class="block-program__tags">
    {#each usedTags as tag}
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
  {/if}

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

  {#if hasMultipleTimeCategories}
  <div class="block-program__filter-time">
    <div class="block-program__filter-time__container"
         class:is-active="{timeFilterStatus !== null}"
    >
        <button class="block-program__filter-time__container__tag"
                on:click={() => setTimeFilterStatus('byDate')}
                class:is-active={timeFilterStatus === 'byDate'}
        >par heure</button>
        <button class="block-program__filter-time__container__tag"
                on:click={() => setTimeFilterStatus('continue')}
                class:is-active={timeFilterStatus === 'continue'}
        >en continu</button>
      {#if timeFilterStatus !== null}
        <button class="block-program__filter-time__container__tag" aria-label="Effacer le filtre horaire" on:click={() => setTimeFilterStatus(null)} style="padding-bottom: 0;">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
        </button>
      {/if}
    </div>
  </div>
  {/if}


</div>

