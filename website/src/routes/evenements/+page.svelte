<div class="s-evenements"
>
    <AppPage
            data="{data}"
    />

    <div class="s-evenements__events"
    >
        <div class="s-evenements__events__tags">
            {#each listOfTags as tag}
                <div class="s-evenements__events__tags__item">{tag}</div>
            {/each}
        </div>

        <div class="s-evenements__events__tags">
            {#each listOfSubcat as tag}
                <div class="s-evenements__events__tags__item" style="background: #3df069">{tag}</div>
            {/each}
        </div>


        <div class="s-evenements__events__events-wrap">
            {#each data.childrenDetails as event}
                <div class="s-evenements__events__events-wrap__item">
                    <div>
                        {event.pageContent.content.datestart}
                    </div>
                    <div>
                        {event.pageContent.content.title}
                    </div>
                    <img
                            alt="cover"
                            src="{event.cover[0]?.resize.reg}"
                    />
                    <div>
                        {event.pageContent.content.category}
                    </div>
                </div>
            {/each}
        </div>
    </div>

</div>


<script lang="ts">
    import {type IPage, type IPageEvents} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";

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
    .s-evenements {

    }
.s-evenements__events__tags {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    box-sizing: border-box;
    padding: 0 2rem;
    width: 100%;
}

    .s-evenements__events__tags__item {
        background: var(--app-color--blue);
        color: white;
        border-radius: 1rem;
        white-space: nowrap;
        padding: .25rem .75rem .35rem;
    }

    .s-evenements__events__events-wrap {
        display: flex;
        text-align: center;
        flex-wrap: wrap;
    }

    .s-evenements__events__events-wrap__item {
        display: block;
        background: var(--app-color--grey--light);
        width: 50%;
        box-sizing: border-box;
        padding: 1rem;
        border-radius: 1rem;
    }
</style>
