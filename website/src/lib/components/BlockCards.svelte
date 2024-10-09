<script lang="ts">
    import type {ApiCardThemeColor, ICards} from "$lib/interfaces/cmsApiResponse";

    export let content: ICards;

    function themeColorMap(themeColorValue: ApiCardThemeColor | undefined): string {
        if (themeColorValue === undefined) return ''

        const mapColoRValue: {[key in ApiCardThemeColor]: string} = {
            '#1754ff' : 'blue',
            '#3df069' : 'green',
            '#b9e6ff' : 'blue-sky',
            '#bea5e6' : 'purple-sky',
            '#ff00fc' : 'pink',
        }
        return mapColoRValue[themeColorValue]
    }

</script>

<div class="s-cards {content.content.style}"
>
    <div class="s-cards__container">
        {#each content.content.cards as card}
            <div class="s-cards__container__card {themeColorMap(card.color)}">
                {#if (card.imageData?.length > 0)}
                    <div class="s-cards__container__card__img">
                        <img class="s-cards__container__card__img__item"
                             src="{card.imageData[0].resize.xxl}"
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
      width: min(70rem, 100%);
      margin: auto;

      &.style2 {

        .s-cards__container__card:nth-child(1) {
          :global(li::before) {
            color: var(--app-color--blue);
          }
        }
      }
    }

    .s-cards__container {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem 1rem;
      justify-content: center;
    }

    .s-cards__container__card {
      display: flex;
      flex-direction: column;
      align-items: center;
      flex-wrap: nowrap;
      width: calc( (100% / 2) - 1rem / 2) ;
      box-sizing: border-box;

      @media (max-width: scss-params.$fp-breakpoint-sm) {
        overflow: hidden;
        width: 100%;
      }
    }

    .s-cards__container__card__img {
      width: 100%;
      padding-top: 100%;
      box-sizing: border-box;
      flex-shrink: 0;
      position: relative;

      .s-cards.style2 .s-cards__container__card & {
        padding-top: 100%;
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

      .s-cards.style2 & {
          background: transparent;
        border: none;
          border-radius: 0;
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
        margin-top: -1.5rem;
      }

        .s-cards.style2 .s-cards__container__card__img + & {
            margin-top: -6rem;

            @media (max-width: 750px) {
                margin-top: -3rem;
            }
        }

      .s-cards.style2 & {
        border: none;
        background: transparent;
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


  .s-cards.style2 .s-cards__container__card {


      border: solid 2px;
      border-radius: 2rem;
      overflow: hidden;

      .s-cards__container__card__content__tilte {
          font-size: clamp(1.5rem, 3vw, 2.5rem);
      }

      .s-cards__container__card__content__content {
          font-size: 1rem;
      }

      &.blue {
          border-color: var(--app-color--blue);

          :global(li::before) {
              color: var(--app-color--green);
          }

          .s-cards__container__card__content__button {
              background: var(--app-color--blue);
              color: var(--app-color--green);
              border-color: var(--app-color--blue);
          }
      }


      &.green {
          border-color: var(--app-color--green);

          :global(li::before) {
              color: var(--app-color--blue);
          }

          .s-cards__container__card__content__tilte { color: var(--app-color--green) }

          .s-cards__container__card__content__button {
              background: var(--app-color--green);
              color: var(--app-color--blue);
              border-color: var(--app-color--green);
          }
      }

      &.blue-sky {
          border-color: var(--app-color--blue--light);

          :global(li::before) {
              color: var(--app-color--purple);
          }

          .s-cards__container__card__content__tilte { color: var(--app-color--green--pastel) }

          .s-cards__container__card__content__button {
              background: var(--app-color--blue--light);
              color: var(--app-color--purple);
              border-color: var(--app-color--blue--light);
          }
      }

      &.purple-sky {
          border-color: var(--app-color--purple);

          :global(li::before) {
              color: var(--app-color--blue--light);
          }

          .s-cards__container__card__content__tilte { color: var(--app-color--purple) }

          .s-cards__container__card__content__button {
              background: var(--app-color--purple);
              color: var(--app-color--blue--light);
              border-color: var(--app-color--purple);
          }
      }

      &.pink {
          border-color: var(--app-color--pink);

          :global(li::before) {
              color: var(--app-color--blue);
          }

          .s-cards__container__card__content__tilte { color: var(--app-color--blue) }

          .s-cards__container__card__content__button {
              background: var(--app-color--blue--light);
              color: var(--app-color--pink);
              border-color: var(--app-color--blue--light);
          }
      }

  }
</style>
