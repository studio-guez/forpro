<div class="s-evenements"
>
    <AppPage
            data="{data}"
    />

    <div class="s-evenements__events"
    >
        <div class="s-evenements__events__tags">
            {#each listOfTags as tag, index}
                <div class="s-evenements__events__tags__item"
                     class:is-active={index === 0}
                     style="--s-evenements__tags-color: white; --s-evenements__tags-bg: var(--app-color--blue);"
                >{tag}</div>
            {/each}
        </div>

        <div class="s-evenements__events__tags">
            {#each listOfSubcat as tag, index}
                <div class="s-evenements__events__tags__item"
                     class:is-active={index === 0}
                     style="--s-evenements__tags-color: black; --s-evenements__tags-bg: var(--app-color--green);"
                >{tag}</div>
            {/each}
        </div>


        <div class="s-evenements__events__events-wrap">
            {#each data.childrenDetails as event}
                <div class="s-evenements__events__events-wrap__item">
                    <div class="s-evenements__events__events-wrap__item__title">
                        {event.pageContent.content.title}
                    </div>
                        <div class="s-evenements__events__events-wrap__item__cover">
                            {#if event.cover[0]}
                                <img class="s-evenements__events__events-wrap__item__cover__image"
                                     alt="cover"
                                     src="{event.cover[0]?.resize.reg}"
                                />
                            {:else}
                                <img class="s-evenements__events__events-wrap__item__cover__image"
                                     alt="cover"
                                     src="empty_images/240625_intro-outro_ForPro_Admin-3.jpg"
                                />
                            {/if}
                            <div class="s-evenements__events__events-wrap__item__cover__date">
                                {formatDate(event.pageContent.content.datestart)}
                            </div>
                        </div>
                    <div class="s-evenements__events__events-wrap__item__tags">
                        {#each event.pageContent.content.category.split(',') as eventItem}
                            <div class="s-evenements__events__events-wrap__item__tags__item">
                                {eventItem}
                            </div>
                        {/each}
                    </div>

                    <div class="s-evenements__events__events-wrap__item__description">
                        <div>
                            {event.pageContent.content.description}
                        </div>
                    </div>

                    <div>
                        <a class="app-button app-button--rounded"
                           href="/"
                        >En savoir plus</a>
                    </div>
                </div>
            {/each}
        </div>
    </div>

</div>


<script lang="ts">
    import {type IPage, type IPageEvents} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";
    import {formatDate} from "$lib/utils/formatDate";

    export let data: IPageEvents;

    const listOfTags = [
        'Soutien',
        'Apprentissage',
        'Formation',
        'Orientation',
        'Entreprises',
        'Art et culture',
        'Cuisine',
        'Do it yourself',
    ]

    const listOfSubcat = [
        'Débat',
        'Conférence',
        'Rencontre',
        'Expo-vente',
        'Jeudredi',
        'Afterwork',
        'Soirée festive',
        'Vitrine métier',
        'Visite',
        'Exposition',
        'Vernissage',
        'Concert',
        'Défilé de mode',
        'Cinéma',
        'Danse',
        'Performance',
        'Résidence',
        'Programme EXPLORE',
        'CAMPUS',
        'Atelier',
    ]

</script>

<style lang="scss">
:global(.s-evenements .s-page) {
    min-height: initial !important;
}

.s-evenements__events__tags {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .5rem;
    box-sizing: border-box;
    padding: 0 2rem;
    width: 100%;
    margin-bottom: .5rem;
}

    .s-evenements__events__tags__item {
        background: var(--s-evenements__tags-bg);
        color: var(--s-evenements__tags-color);
        border-radius: 1rem;
        white-space: nowrap;
        padding: .15em 1em .35em;
        border: solid 2px var(--s-evenements__tags-bg);

        &.is-active {
            color: black;
            background: white;
        }
    }

    .s-evenements__events__events-wrap {
        display: flex;
        text-align: center;
        flex-wrap: wrap;
        gap: var(--app-gutter_regular);
        justify-content: center;
        box-sizing: border-box;
        width: 100%;
        padding: 1rem 1rem 5rem;
    }

    .s-evenements__events__events-wrap__item {
        display: block;
        width: calc( 50% - (var(--app-gutter_regular) / 2 ));
        max-width: 20rem;
        box-sizing: border-box;
        padding: 2rem 1rem;
        border-radius: 1rem;
        position: relative;
        background: var(--app-color-beige);
        //background: var(--app-color--blue--light);
        border: solid 2px var(--app-color--blue);
        margin-top: 2rem;
    }

    .s-evenements__events__events-wrap__item__cover__date {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%, 50%);
        background: white;
        font-size: .75rem;
        padding: .15em .75em .3em;
        border-radius: 1em;
    }

    .s-evenements__events__events-wrap__item__title {
        font-size: 1.65rem;
        line-height: 1em;
        font-weight: 900;
        color: white;
        background: var(--fp-color-makerlab);
        padding: var(--app-gutter_regular);
        top: 0;
        left: 0;
        width: 100%;
        box-sizing: border-box;
        border-radius: 2rem;
        transform: translate( 0, -4rem );
        margin-bottom: -2rem;
    }

    .s-evenements__events__events-wrap__item__cover {
        position: relative;
        margin-bottom: 1rem;
    }

    .s-evenements__events__events-wrap__item__cover__image {
        display: block;
        width: 100%;
        aspect-ratio: 5/3;
        object-fit: cover;
        border-radius: 1rem;

        &.s-evenements__events__events-wrap__item__cover--default {
            background: var(--app-color--blue);
            display: flex;
            justify-content: center;
            align-items: center;

            > div {
                font-size: 3rem;
                flex-wrap: nowrap;
                font-weight: 900;
                line-height: 1em;
                transform: rotate(-5deg);
            }
        }
    }

.s-evenements__events__events-wrap__item__tags {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .5rem;
    padding: .5rem 0;
}

.s-evenements__events__events-wrap__item__tags__item {
    display: block;
    background: var(--app-color--blue);
    color: white;
    padding: .15em .5em .35em;
    font-size: .75rem;
    border-radius: 1em;
}

.s-evenements__events__events-wrap__item__description {
    font-weight: 500;
    font-size: 1rem;
    line-height: 1.15em;
    padding-bottom: 1rem;
    text-align: left;
}
</style>
