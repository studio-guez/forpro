<script lang="ts">
    import type {ICapsules} from "$lib/interfaces/cmsApiResponse";

    export let content: ICapsules;
</script>

<div class="s-block-capsule"
>
    {#each content.content.capsules as capsule}
        <div class="s-block-capsule__item app-flex app-flex--column app-flex--align_center app-flex--gap_half">
            <div class="s-block-capsule__item__content">
                <h3 class="s-block-capsule__item__content__title"
                >{capsule.title}</h3>
                <div class="s-block-capsule__item__content__text app-remove-margin-child"
                >{@html capsule.text}</div>
            </div>

            {#if capsule.style === 'style1'}
                <img class="s-block-capsule__item__img" src="/svg/Forme1-03.svg"
                     alt="graphique pour illustrer un item"/>
            {:else if capsule.style === 'style2' }
                <img class="s-block-capsule__item__img" src="/svg/Forme1-11.svg"
                     alt="graphique pour illustrer un item"/>
            {:else if capsule.style === 'style3' }
                <img class="s-block-capsule__item__img" src="/svg/Forme1-12.svg"
                     alt="graphique pour illustrer un item"/>
            {:else if capsule.style === 'style4' }
                <img class="s-block-capsule__item__img" src="/svg/Forme1-14.svg"
                     alt="graphique pour illustrer un item"/>
            {/if}
        </div>
    {/each}
</div>

<style lang="scss">
  $block-capsule-breakpoint-md: 800px;


    .s-block-capsule {


      --max-width: 50rem;
      --title-max-font-size: 2.5rem;
      --title-max-content-size: 1.5rem;
      --title-min-content-size: 1rem;
      --title-gap: 4rem;



      container-type: inline-size;
      container-name: block-capsule;
      width: 100%;
      max-width: var(--max-width);
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: var(--title-gap);

      @container block-capsule (width < #{$block-capsule-breakpoint-md}) {
        grid-template-columns: repeat(1, 1fr);
      }
    }
    .s-block-capsule__item {
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: center;
      flex-basis: calc( (100% / 2) - ( var(--title-gap) / 2 ) );

      @container block-capsule (width < #{$block-capsule-breakpoint-md}) {
        flex-basis: 80%;
      }
    }
    .s-block-capsule__item__content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }

    .s-block-capsule__item__content__title {
      margin: 0;
      padding: .5em;
      color: var(--app-color--pink);
      line-height: 1em;
      font-size: min(4vw, var(--title-max-font-size));
      text-align: center;
      font-weight: 600;

      @container block-capsule (width < #{$block-capsule-breakpoint-md}) {
        font-size: max(6vw, 1.25rem);
      }
    }
    .s-block-capsule__item__content__text {
      color: var(--app-color--blue);
      line-height: 1em;
      font-size: clamp(var(--title-min-content-size), 2.5vw, var(--title-max-content-size));
      width: 100%;
      text-align: center;
      font-weight: 500;
    }
    .s-block-capsule__item__img {
      display: block;
      position: relative;
      width: 75%;
      height: auto;
    }
</style>


