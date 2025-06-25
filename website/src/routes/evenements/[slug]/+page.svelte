<div class="s-page-events-slug s-page-events-slug--events__slug"
>
    <div class="s-page-events-slug__hero">
        <h1 class="s-page-events-slug__hero__title fp-heading-h3"
        >{data.pageInfo.title}</h1>
        <div class="s-page-events-slug__hero__date">
          {@html formatDate(data.pageInfo.datestart)}
          <div>{formatTime(data.pageInfo.hourstart)}</div>
        </div>
    </div>
  <div class="s-page-events-slug__ariane">
    <div class="s-page-events-slug__ariane__wrap">
      <a href="/evenements">événements</a>
      <svg style="display: block; fill: currentColor; height: 1em; width: auto; margin-left: .5em;"
              xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/></svg>
      {data.pageInfo.title}
    </div>
  </div>
    <AppPage
            data="{data}"
    />
</div>


<script lang="ts">
    import {type IPage_Event} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";
    import {formatDate} from "$lib/utils/formatDate";
    import {formatTime} from "$lib/utils/formatTime";

    export let data: IPage_Event;

</script>

<style lang="scss">
    @use "../../../style/_scss-params";

    .s-page-events-slug {
        margin-bottom: 5rem;
    }

    .s-page-events-slug__hero {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .s-page-events-slug__hero__date {
        background: var(--app-color--blue);
        color: white;
        white-space: nowrap;
        padding: .4em 1.5em .6em;
        border-radius: 4em;
        font-weight: 800;
        font-size: 1rem;
        line-height: 1.25em;
        &:first-letter {
          text-transform: uppercase;
        }

        > div {
          font-weight: 400;
        }
    }

    .s-page-events-slug__hero__title {
        font-weight: 600;
        color: var(--app-color--pink);
    }

    .s-page-events-slug__hero {
        overflow: hidden;
        box-sizing: border-box;
        width: 100%;
        padding: 4rem 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;

        &.first-child-is-animation {
            padding-top: 0;
            padding-bottom: 1rem;
            margin-bottom: -2rem;
        }

        @media (max-width: scss-params.$fp-breakpoint-xs) {
            padding-top: 1rem;
            padding-bottom: 3rem;
        }
    }

    .s-page-events-slug__ariane {
      width: 100%;
      position: sticky;
      top: 0;
      margin-bottom: 1rem;
      display: flex;
    }

    .s-page-events-slug__ariane__wrap {
      //width: 100%;
      margin-left: auto;
      margin-right: auto;
      max-width: 62.5rem;
      background: white;
      padding: .5rem 2rem;
      border-radius: 1rem;
      border: 2px solid var(--app-color--blue);
      color: var(--app-color--blue);
      font-size: .75rem;
      display: flex;
      align-items: center;
    }
</style>
