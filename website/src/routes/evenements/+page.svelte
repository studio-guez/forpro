<div class="s-evenements"
>
    <AppPage
            data="{data}"
    />

    <div class="s-evenements__events"
    >
        <div class="s-evenements__events__search">
            <div class="s-evenements__events__search__bar">
                <input type="text"
                       class="s-evenements__events__search__bar__input"
                       bind:value={searchValue}
                >
                <div class="s-evenements__events__search__bar__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/></svg>
                </div>
            </div>
        </div>
        <div class="s-evenements__events__tags">
            {#each listOfTags as tag, index}
                <div class="s-evenements__events__tags__item"
                     on:click={() => activeTag === tag ? activeTag = null : activeTag = tag}
                     class:is-active={ activeTag === tag }
                     style="--s-evenements__tags-color: var(--app-color--blue); --s-evenements__tags-bg: {tag.color};"
                >{tag.title}</div>
            {/each}
        </div>

        <div class="s-evenements__events__tags">
            {#each listOfSubcategories as tag, index}
                <div class="s-evenements__events__tags__item"
                     on:click={() => activeSubCategory.includes(tag) ? activeSubCategory = activeSubCategory.filter(t => t !== tag) : activeSubCategory = [...activeSubCategory, tag] }
                     class:is-active={ activeSubCategory.includes(tag) }
                     style="--s-evenements__tags-color: white; --s-evenements__tags-bg: {tag.color};"
                >{tag.title}</div>
            {/each}
        </div>


        <div class="s-evenements__events__events-wrap">
            {#each events as event}
                <div class="s-evenements__events__events-wrap__item">
                    <div class="s-evenements__events__events-wrap__item__title">
                        {event.pageContent.content.title}
                    </div>
                    {#if event.pageContent.content.withpartner === 'true'}
                    <div class="s-evenements__events__events-wrap__item__partner">
                        partenaires
                    </div>
                    {/if}
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
                            {@html formatDate(event.pageContent.content.datestart)}
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

                    <div class="s-evenements__events__events-wrap__item__details">
                        <a class="app-button app-button--rounded"
                           href="{event.pageContent.uri}"
                        >
                            {#if event.pageContent.content.parent_page_link_text && event.pageContent.content.parent_page_link_text.length > 0}
                                {event.pageContent.content.parent_page_link_text}
                            {:else }
                            En savoir plus
                            {/if}
                        </a>
                    </div>
                </div>
            {/each}
        </div>


        <div class="s-evenements__events__events-wrap s-evenements__events__events-wrap--archive">
            <h3 style="width: 100%;">Archives</h3>
            {#each eventArchived as event}
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
                            {@html formatDate(event.pageContent.content.datestart)}
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

                    <div class="s-evenements__events__events-wrap__item__details">
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
    import {type IPageEvents} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";
    import {formatDate} from "$lib/utils/formatDate";

    export let data: IPageEvents;

    let activeTag: null | Tag = null
    let searchValue: string = ''

    $: events = data.childrenDetails.filter(event => {

        const stringEventDateEnd = event.pageContent.content.dateend || event.pageContent.content.datestart

        const eventDateEnd = new Date(stringEventDateEnd)
        eventDateEnd.setHours(0, 0, 0, 0)

        const today = new Date()
        today.setHours(0, 0, 0, 0)

        const eventIsPast =  eventDateEnd < today

        const isVisible = !eventIsPast ? true : event.pageContent.content.isarchive !== 'true'

        const matchesSearch = searchValue.length === 0 ||
                [event.pageContent.content.title, event.pageContent.content.description, event.pageContent.content.body]
                .some(text => text?.toLowerCase().includes(searchValue.toLowerCase()))

        return isVisible && matchesSearch
    })



    $:eventArchived     = data.childrenDetails.filter(event => {

        const stringEventDateEnd = event.pageContent.content.dateend || event.pageContent.content.datestart

        const eventDateEnd = new Date(stringEventDateEnd)
        eventDateEnd.setHours(0, 0, 0, 0)

        const today = new Date()
        today.setHours(0, 0, 0, 0)

        const eventIsPast =  eventDateEnd < today

        const isVisible = eventIsPast && event.pageContent.content.isarchive === 'true'

        const matchesSearch = searchValue.length === 0 ||
                [event.pageContent.content.title, event.pageContent.content.description, event.pageContent.content.body]
                        .some(text => text?.toLowerCase().includes(searchValue.toLowerCase()))

        return isVisible && matchesSearch
    })


    type Tag = {
        title: string;
        color: string;
    }

    const listOfTags: Tag[] = [
        {
            title: "parents & entourage",
            color: '#3df069',
        },
        {
            title: "jeunes",
            color: '#3df069',
        },
        {
            title: "entreprises",
            color: '#3df069',
        },
        {
            title: "tout public",
            color: '#3df069',
        },
    ]

    let activeSubCategory: Tag[] = []

    const listOfSubcategories: Tag[] = [
        {color: '#1754ff', title: "s’orienter",},
        {color: '#1754ff', title: "trouver du soutien",},
        {color: '#1754ff', title: "accompagner",},
        {color: '#1754ff', title: "former",},
        {color: '#1754ff', title: "s’émerveiller",},
        {color: '#1754ff', title: "manger",},
        {color: '#1754ff', title: "fabriquer",},
        {color: '#1754ff', title: "expérimenter",},
    ]

</script>

<style lang="scss">
:global(.s-evenements .s-page) {
    min-height: initial !important;
}

.s-evenements__events {
    margin-top: 5rem;
}

.s-evenements__events__search {
    display: flex;
    align-content: center;
    justify-content: center;
    margin-bottom: 1rem;

    .s-evenements__events__search__bar {
        display: flex;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
        border: solid 3px var(--app-color--blue);
        border-radius: 2rem;

        .s-evenements__events__search__bar__icon {
            width: 2rem;
            height: 2rem;
            border-radius: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--app-color--blue);
            cursor: pointer;

            svg {
                display: block;
                height: 1rem;
                width: auto;
                fill: white;
            }
        }

        .s-evenements__events__search__bar__input {
            cursor: pointer;
            all: unset;
            box-sizing: border-box;
            padding: .5rem 1rem;
            color: var(--app-color--blue);
        }

    }
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
    user-select: none;
    cursor: pointer;
}

    .s-evenements__events__tags__item {
        background: var(--s-evenements__tags-bg);
        color: var(--s-evenements__tags-color);
        border-radius: 1rem;
        white-space: nowrap;
        padding: .15em 1em .35em;
        border: solid 2px var(--s-evenements__tags-bg);

        &.is-active {
            color: var(--app-color--blue);
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
        width: calc( 33% - (var(--app-gutter_regular) / 1 ));
        //max-width: 20rem;
        box-sizing: border-box;
        padding: 2rem 1rem;
        border-radius: 1rem;
        position: relative;
        background: var(--app-color-beige);
        //background: var(--app-color--blue--light);
        border: solid 2px var(--app-color--blue);
        margin-top: 2rem;
        cursor: pointer;

        .s-evenements__events__events-wrap--archive & {
            filter: grayscale(100%);
            transition: filter .25s ease-in-out;
            width: 100%;
            display: flex;
            padding: .5rem;
            border-radius: 0;
            border: none;
            border-top: solid 2px;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            background: transparent;

            &:last-child {
                border-bottom: 2px solid;
            }
        }
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

        .s-evenements__events__events-wrap--archive & {
            position: relative;
            white-space: nowrap;
            top: initial;
            transform: none;
            left: 0;
            font-size: .65rem;
            margin: 0;
        }
    }

    .s-evenements__events__events-wrap__item__title {
        font-size: 1.25rem;
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

        .s-evenements__events__events-wrap--archive & {
            transform: translate( 0, 0 );
            background: none;
            text-align: left;
            margin-bottom: 0;
            color: black;
            font-size: 1.25rem;
            padding: 0;
            width: calc( 100% - 9rem);
        }
    }

    .s-evenements__events__events-wrap__item__partner {
        position: absolute;
        top: 0;
        right: 0;
        background: var(--app-color--green);
        transform: translate(40%, 0) rotate(25deg);
        padding: .15em .5em .35em;
        font-size: .65rem;
        border-radius: 2em;
        color: var(--app);
    }

    .s-evenements__events__events-wrap__item__cover {
        position: relative;
        margin-bottom: 1rem;

        .s-evenements__events__events-wrap--archive & {
            margin-bottom: 0;
        }
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

        .s-evenements__events__events-wrap--archive & {
            display: none;
        }
    }

.s-evenements__events__events-wrap__item__tags {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: .5rem;
    padding: .5rem 0;
    order: 3;

    .s-evenements__events__events-wrap--archive & {
        order: initial;
        margin-top: 1rem;
    }
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

    .s-evenements__events__events-wrap--archive & {
        display: none;
    }
}
</style>
