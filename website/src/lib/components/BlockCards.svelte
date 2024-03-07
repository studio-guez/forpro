<script lang="ts">
    import type {ICards} from "$lib/interfaces/cmsApiResponse";

    export let content: ICards;
</script>

<div class="s-cards {content.content.style}"
>
    <div class="s-cards__container">
        {#each content.content.cards as card}
            <div class="s-cards__container__card">
                {#if (card.imageData?.length > 0)}
                    <div class="s-cards__container__card__img">
                        <img class="s-cards__container__card__img__item"
                             src="{card.imageData[0].resize.large}"
                             alt="illustration pour la carte"
                        />
                    </div>
                {/if}

                <div
                        class="s-cards__container__card__content"
                >
                    <div style="width: 100%">
                        <h3 class="s-cards__container__card__content__tilte">{card.title}</h3>
                        <div class="s-cards__container__card__content__content app-typo_text-content">{@html card.text}</div>
                    </div>
                    {#if (card.link)}
                        <a class="s-cards__container__card__content__button app-button app-button--rounded"
                           href="{card.link}"
                        >En savoir plus</a>
                    {/if}
                </div>

            </div>
        {/each}
    </div>
</div>


<style lang="scss">
  @use "../../style/_scss-params";

    .s-cards {
      container-type: inline-size;

      &.style2 {
        width: min(50rem, 100%);
        margin: auto;

        .s-cards__container__card:nth-child(1) {
          :global(li::before) {
            color: var(--app-color--blue);
          }
        }
      }
    }

    .s-cards__container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 2rem 1rem;

      @container (width < 900px) {
        grid-template-columns: repeat(1, 1fr);
      }
    }

    .s-cards__container__card {
      display: flex;
      flex-direction: column;
      align-items: center;
      flex-wrap: nowrap;

      .s-cards.style2 &:nth-child(1) {
        grid-column: 1/3;

        @media (max-width: scss-params.$fp-breakpoint-xs) {
          grid-column: unset;
        }
      }

      @media (max-width: scss-params.$fp-breakpoint-xs) {
        overflow: hidden;
      }
    }

    .s-cards__container__card__img {
      width: 100%;
      padding-top: 100%;
      box-sizing: border-box;
      flex-shrink: 0;
      position: relative;

      .s-cards.style2 .s-cards__container__card:nth-child(1) & {
        padding-top: 50%;
        @media (max-width: scss-params.$fp-breakpoint-xs) {
          padding-top: 100%;
        }
      }
    }

    .s-cards__container__card__img__item {
      background: var(--app-color--blue);
      position: absolute;
      top: 0;
      left: 0;
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 2rem;
      box-sizing: border-box;
      border: solid var(--app-line-with) var(--app-color--pink);

      .s-cards.style2 .s-cards__container__card:nth-child(1) & {
        border-color: var(--app-color--blue);
      }
    }

    .s-cards__container__card__content {
      border: solid var(--app-line-with) var(--app-color--pink);
      background: var(--app-color--grey--light);
      padding: 1rem;
      border-radius: 2rem;
      box-sizing: border-box;
      height: 100%;
      flex-shrink: 1;
      z-index: 1;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-direction: column;
      width: 100%;

      .s-cards:not(.style2) .s-cards__container__card:nth-child(2n) & {
        background: var(--app-color--pink);
      }

      .s-cards__container__card__img + & {
        border-top-color: var(--app-color--grey--light);
        margin-top: -1.5rem;
      }

      .s-cards.style2 & {
        border: solid var(--app-line-with) var(--app-color--pink);
        background: var(--app-color--grey--light);
      }

      .s-cards.style2 .s-cards__container__card:nth-child(1) & {
        border-color: var(--app-color--blue);
      }
    }


    .s-cards__container__card__content__button {
      margin: 2rem 0;
      text-align: center;
      box-sizing: border-box;
    }

    .s-cards__container__card__content__tilte {
      color: var(--app-color--pink);
      font-size: 1.75rem;
      line-height: 1em;
      margin-top: 0;
      margin-bottom: 1em;
      text-align: center;
      font-weight: 600;

      @media (max-width: scss-params.$fp-breakpoint-xs) {
        font-size: 1.15rem;
      }

      .s-cards:not(.style2) .s-cards__container__card:nth-child(2n) & {
        color: white;
      }

      .s-cards.style2 .s-cards__container__card:nth-child(1) & {
        color: var(--app-color--blue);
      }
    }

    .s-cards__container__card__content__content {
      margin: auto;
      margin-top: 1rem;
      max-width: 30em;
    }
</style>
