<script lang="ts">
    import type {ICapsules} from "$lib/interfaces/cmsApiResponse";

    export let content: ICapsules;
</script>

<div class="s-block-capsule {content.content.style} app-flex app-flex--justify_center app-flex--wrap"
>
    {#each content.content.capsules as capsule}
        <div class="app-flex__basis-1-2">
                <div class="s-block-capsule__item app-flex app-flex--column app-flex--align_center app-flex--gap_half">
                <div class="s-block-capsule__item__content">
                    <h3 class="s-block-capsule__item__content__title"
                    >{capsule.title}</h3>
                    <div class="s-block-capsule__item__content__text app-remove-margin-child"
                    >{@html capsule.text}</div>
                </div>

                {#if capsule.style === 'style1'}
                    <img class="s-block-capsule__item__img" src="/svg/Forme1-03.svg" alt="graphique pour illustrer un item"/>
                {:else if capsule.style === 'style2' }
                    <img class="s-block-capsule__item__img" src="/svg/Forme1-11.svg" alt="graphique pour illustrer un item"/>
                {:else if capsule.style === 'style3' }
                    <img class="s-block-capsule__item__img" src="/svg/Forme1-12.svg" alt="graphique pour illustrer un item"/>
                {:else if capsule.style === 'style4' }
                    <img class="s-block-capsule__item__img" src="/svg/Forme1-14.svg" alt="graphique pour illustrer un item"/>
                {/if}
            </div>
        </div>
    {/each}
</div>

<style lang="scss">
    .s-block-capsule__item {
      position: relative;
      width: 100%;
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
      flex-direction: column;
    }

    .s-block-capsule__item__content__title {
      margin: 0;
      padding: .4rem 1rem .6rem;
      color: var(--app-color--pink);
      line-height: 1em;
      font-size: 4vw;
      text-align: center;
      font-weight: 500;
    }
    .s-block-capsule__item__content__text {
      padding-top: 1rem;
      padding-bottom: 1rem;
      color: var(--app-color--blue);
      line-height: 1em;
      font-size: 2.5vw;
      width: 100%;
      text-align: center;
    }
    .s-block-capsule__item__img {
      display: block;
      position: relative;
      width: 75%;
      height: auto;
    }
</style>


