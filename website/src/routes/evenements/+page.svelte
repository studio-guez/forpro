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
                  <AppEventTile
                      event={event}
                  />
              </div>
            {/each}
        </div>

      {#if eventArchived.length > 0}
        <div class="s-evenements__events__events-wrap">
            <h3 style="width: 100%;">Archives</h3>
            {#each eventArchived as event}
                <AppEventTile
                        event={event}
                        isArchive="{true}"

                />
            {/each}
        </div>
      {/if}

    </div>

</div>


<script lang="ts">
    import {type IPageEvents} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";
    import {formatDate} from "$lib/utils/formatDate";
    import AppEventTile from "$lib/components/AppEventTile.svelte";

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

        const isVisible = !eventIsPast

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
    padding: .05em .5em .25em;
    border: solid 2px var(--s-evenements__tags-bg);
    font-size: .75rem;

    &.is-active {
        color: var(--app-color--blue);
        background: white;
    }
}

.s-evenements__events__events-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: var(--app-gutter_regular);
    justify-content: center;
    box-sizing: border-box;
    width: 100%;
    padding: 1rem 1rem 5rem;
    align-items: stretch;

  h3 {
    text-align: center;
  }
}

.s-evenements__events__events-wrap__item {
  position: relative;
  width: calc( (100% + var(--app-gutter_regular)) / 3 - var(--app-gutter_regular));
  min-width: 20rem;
  box-sizing: border-box;

  @media (max-width: 960px) {
    width: 100%;
    min-width: initial;
  }
}
</style>
