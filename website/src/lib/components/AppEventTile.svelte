<script lang="ts">
import {formatDate} from "$lib/utils/formatDate.js";
import type {IChildrenDetils__event} from "$lib/interfaces/cmsApiResponse.js";
import {formatTime} from "$lib/utils/formatTime";

export let event: IChildrenDetils__event;

export let isArchive = false;

</script>

<div class="app-event-tile"
     class:app-event-tile--is-archive="{isArchive}"
>
  <div class="app-event-tile__title">
    {event.pageContent.content.title}
  </div>
  {#if !isArchive}
    <div class="app-event-tile__cover">

      {#if event.pageContent.content.withpartner === 'true'}
        <div class="app-event-tile__cover__partner">
          <div>
            Événement
            <br>partenaire
          </div>
        </div>
      {/if}

      {#if event.cover[0]}
        <img class="app-event-tile__cover__image"
             alt="cover"
             src="{event.cover[0]?.resize.reg}"
        />
      {:else}
        <img class="app-event-tile__cover__image"
             alt="cover"
             src="empty_images/240625_intro-outro_ForPro_Admin-3.jpg"
        />
      {/if}
    </div>
  {/if}
  <div class="app-event-tile__tags">
    <div class="app-event-tile__tags__item">
      {#if event.pageContent.content.dateend && event.pageContent.content.datestart !== event.pageContent.content.dateend}
        Du
      {/if}
      {@html formatDate(event.pageContent.content.datestart, isArchive, ! event.pageContent.content.dateend)}
      {#if event.pageContent.content.dateend && event.pageContent.content.datestart !== event.pageContent.content.dateend}
        {#if !isArchive}<br>{/if}
        au {@html formatDate(event.pageContent.content.dateend, false, false)}
      {/if}
      {#if !isArchive}
        <div>
          {#if event.pageContent.content.hourstart}
          {formatTime(event.pageContent.content.hourstart)}
          {/if}
          {#if event.pageContent.content.hourend}
            - {formatTime(event.pageContent.content.hourend)}
          {/if}
        </div>
      {/if}
    </div>
  </div>

  <div class="app-event-tile__description">
    <div>
      {@html event.pageContent.content.description}
    </div>
  </div>

  <div class="app-event-tile__details">
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


