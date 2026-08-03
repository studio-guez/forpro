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


