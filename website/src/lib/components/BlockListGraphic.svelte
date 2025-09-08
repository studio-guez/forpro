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

<style lang="scss">
  @use "../../style/_scss-params";

  .s-block-list-graphic {
    width: 100%;
    display: flex;
    justify-content: center;
    padding-top: 5rem;
  }

  .s-block-list-graphic__content {
    display: flex;
    width: 100%;
    max-width: 70rem;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    user-select: none;
    cursor: pointer;
    gap: 2rem 1rem;
  }

  .s-block-list-graphic__content__item {
    background: var(--app-color--orange);
    color: var(--app-color--blue);
    padding: .5rem;
    width: 25%;
    text-align: center;
    position: relative;
    min-width: 8rem;

    &:before, &:after {
      content: '';
      display: block;
      position: absolute;
      background: var(--app-color--orange);
      width: 100%;
      height: 1rem;
    }

    &:nth-child(2n) {
      background: var(--app-color--blue);
      color: var(--app-color--orange);

      &:before, &:after {
        background: var(--app-color--blue);
      }
    }

    //1
    &:nth-child(1n) {
      transform: translate(0%) rotate(-10deg);
      max-width: 10rem;
      padding-top: 0rem;
      padding-bottom: 2rem;

      &:before {
        width:calc(100% + 2rem);
        top: 0;
        left: 50%;
        transform: translate(-50%, -90%);
      }
      &:after {
        width:calc(100% + 2rem);
        top: 100%;
        left: 50%;
        transform: translate(-50%, -90%);
      }
    }


    //2
    &:nth-child(2n) {
      transform: translate(0%) rotate(20deg);
      max-width: 10rem;
      padding-top: 0rem;
      padding-bottom: 2rem;

      &:before {
        width:calc(100% + 2rem);
        top: 0;
        left: 50%;
        transform: translate(-50%, -90%);
      }
      &:after {
        width:calc(100% + 2rem);
        top: 100%;
        left: 50%;
        transform: translate(-50%, -90%);
      }
    }









    // 3
    &:nth-child(3n) {
      transform: translate(0%, 0%) rotate(-20deg);
      padding-top: 1rem;
      padding-bottom: 2rem;

      &:before {
        width: 100%;
        top: 0;
        left: 50%;
        transform: translate(-50%, -90%);
        border-top-left-radius: 2rem;
        border-top-right-radius: 2rem;

      }
      &:after {
        width:100%;
        top: 100%;
        left: 50%;
        transform: translate(-50%, -10%);
        border-bottom-left-radius: 2rem;
        border-bottom-right-radius: 2rem;
      }
    }






    &:nth-child(4n) {
      transform: translate(0%, 0%) rotate(15deg);
    }






    &:nth-child(5n) {
      transform: translate(0%, 0%) rotate(-25deg);
    }
    &:nth-child(6n) {
      transform: translate(0%, 0%) rotate(5deg);
    }
    &:nth-child(7n) {
      transform: translate(0%, 0%) rotate(-10deg);
    }
    &:nth-child(8n) {
      transform: translate(0%, 0%) rotate(5deg);
    }


  }


  :global(.s-block-list-graphic__content__item.animate-ding-ding) {
    animation: ding-ding 0.5s ease-in-out;
    transform-origin: center center;
  }


  @keyframes ding-ding {
    0% {
      transform: rotate(-10deg);
    }
    10% {
      transform: rotate(10deg);
    }
    20% {
      transform: rotate(-20deg);
    }
    50% {
      transform: rotate(20deg);
    }
    70% {
      transform: rotate(-10deg);
    }
    80% {
      transform: rotate(15deg);
    }
    100% {
      transform: rotate(-15deg);
    }
  }

</style>
