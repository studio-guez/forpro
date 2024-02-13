<script lang="ts">
    import type {ICards} from "$lib/interfaces/cmsApiResponse";

    export let content: ICards;
</script>

<div class="s-cards"
>
    {#each content.content.cards as card}
        <div class="s-cards__card">
            {#if (card.imageData?.length > 0)}
                <div class="s-cards__card__img">
                    <img class="s-cards__card__img__item"
                         src="{card.imageData[0].resize.large}"
                         alt="illustration pour la carte"
                    />
                </div>
            {/if}

            <div
                    class="s-cards__card__content"
            >
                <h3 class="s-cards__card__content__tilte">{card.title}</h3>
                <div class="s-cards__card__content__content app-typo_text-content">{@html card.text}</div>
            </div>

            {#if (card.link)}
                <div style="width: 100%">
                    <a class="s-cards__card__button app-button app-button--rounded"
                       href="{card.link}"
                    >En savoir plus</a>
                </div>
            {/if}
        </div>
    {/each}
</div>


<style lang="scss">
    .s-cards {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem 1rem;
    }

    .s-cards__card {
      display: flex;
      flex-direction: column;
      align-items: center;
      flex-wrap: nowrap;
    }

    .s-cards__card__img {
      width: 100%;
      padding-top: 100%;
      box-sizing: border-box;
      flex-shrink: 0;
      position: relative;
    }

    .s-cards__card__img__item {
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
    }

    .s-cards__card__content {
      border: solid var(--app-line-with) var(--app-color--pink);
      background: var(--app-color--grey--light);
      padding: 1rem;
      border-radius: 2rem;
      box-sizing: border-box;
      height: 100%;
      flex-shrink: 1;
      z-index: 1;

      .s-cards__card:nth-child(2n) & {
        background: var(--app-color--pink);
      }

      .s-cards__card__img + & {
        border-top-color: var(--app-color--grey--light);
        margin-top: -1.5rem;
      }
    }

    .s-cards__card__button {
      margin-top: 1rem;
      width: 100%;
      text-align: center;
      box-sizing: border-box;
      flex-shrink: 0;
    }

    .s-cards__card__content__tilte {
      color: var(--app-color--pink);
      font-size: 1.75rem;
      line-height: 1em;
      margin-top: 0;
      text-align: center;
      font-weight: 600;

      .s-cards__card:nth-child(2n) & {
        color: white;
      }
    }

    .s-cards__card__content__content {
      margin-top: 1rem;
    }
</style>
