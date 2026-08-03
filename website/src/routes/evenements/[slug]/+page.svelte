<div class="s-page-events-slug s-page-events-slug--events__slug"
>
    <div class="s-page-events-slug__hero">
        <h1 class="s-page-events-slug__hero__title fp-heading-h3"
        >{data.pageInfo.title}</h1>
        <div class="s-page-events-slug__hero__date">
          {#if data.pageInfo.dateend && data.pageInfo.datestart !== data.pageInfo.dateend}
            Du
          {/if}
          {@html formatDate(data.pageInfo.datestart, false, ! data.pageInfo.dateend)}
          {#if data.pageInfo.dateend && data.pageInfo.datestart !== data.pageInfo.dateend}
            <br>
            au {@html formatDate(data.pageInfo.dateend, false, false)}
          {/if}
            <div>
              {#if data.pageInfo.hourstart}
                {formatTime(data.pageInfo.hourstart)}
              {/if}
              {#if data.pageInfo.hourend}
                - {formatTime(data.pageInfo.hourend)}
              {/if}
            </div>
        </div>
    </div>
  <div class="s-page-events-slug__ariane">
    <a href="/evenements" class="s-page-events-slug__ariane__wrap">
      <svg style="display: block; fill: currentColor; height: 1em; width: auto;top: .15em;position: relative;"
              xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M400-80 0-480l400-400 71 71-329 329 329 329-71 71Z"/>
      </svg>
      <div>Agenda</div>
    </a>
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

