<script lang="ts">
  import type {IBlockListGraphic} from "$lib/interfaces/cmsApiResponse";
  import {onMount} from "svelte";
  import {scrollToBlockByIndex} from "$lib/utils/scrollToBlockByIndex";

  export let data: IBlockListGraphic


  onMount(() => {
      runAnimation()
      setInterval(runAnimation, 3_000)
  })

  function runAnimation() {
      const items = document?.querySelectorAll('.s-block-list-graphic__content__item')

      const randomIndex = Math.floor(Math.random() * items.length)

      const rendomIten = items[randomIndex]

      rendomIten.classList.add('animate-ding-ding')

      rendomIten.addEventListener('animationend', () => {
          rendomIten.classList.remove('animate-ding-ding')
      }, { once: true })
  }

</script>

<section class="s-block-list-graphic">
  <div class="s-block-list-graphic__content">
    {#each data.content.items as item}
      <div class="s-block-list-graphic__content__item"
           role="button"
           tabindex="0"
           on:keydown={() => scrollToBlockByIndex(item.scrolltoblock)}
           on:click={() => scrollToBlockByIndex(item.scrolltoblock)}
      >{item.title}</div>
    {/each}
  </div>
</section>

