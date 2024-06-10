<script lang="ts">
    import type {IProfiles} from "$lib/interfaces/cmsApiResponse";

    export let content: IProfiles;
</script>

<div class="s-profiles {content.content.style} app-grid--column-3 app-grid--sm--column-1">
    {#each content.content.profiles as profile}
        <div class="s-profiles__profile"
             class:with-img="{profile.imageData?.length > 0}"
        >

            {#if (profile.imageData?.length > 0)}
                <img class="s-profiles__profile__img" alt="profile illustration" src="{profile.imageData[0].resize.reg}">
            {/if}
            <h3 class="s-profiles__profile__title">{profile.title}</h3>
            <h4 class="s-profiles__profile__subtitle">{profile.subtitle}</h4>
            <div class="s-profiles__profile__description">
                {@html profile.description}
            </div>

            <div class="s-profiles__profile__links app-flex">
                {#if (profile.mailto)}
                    <a href="mailto:{profile.mailto}" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48">
                            <path d="M140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm340-302L140-685v465h680v-465L480-462Zm0-60 336-218H145l335 218ZM140-685v-55 520-465Z"/>
                        </svg>
                    </a>
                {/if}
                {#if (profile.lien)}
                    <a href="{profile.lien}" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" height="48" viewBox="0 -960 960 960" width="48">
                            <path d="M450-280H280q-83 0-141.5-58.5T80-480q0-83 58.5-141.5T280-680h170v60H280q-58.333 0-99.167 40.765-40.833 40.764-40.833 99Q140-422 180.833-381q40.834 41 99.167 41h170v60ZM325-450v-60h310v60H325Zm185 170v-60h170q58.333 0 99.167-40.765 40.833-40.764 40.833-99Q820-538 779.167-579 738.333-620 680-620H510v-60h170q83 0 141.5 58.5T880-480q0 83-58.5 141.5T680-280H510Z"/>
                        </svg>
                    </a>
                {/if}
            </div>
        </div>
    {/each}
</div>

<style lang="scss">
  @use "../../style/_scss-params";

  .s-profiles {
    grid-row-gap: 4rem;
  }

  .s-profiles__profile {
    &.with-img {
      text-align: center;
    }
  }

    .s-profiles__profile__title {
      color: var(--app-color--blue);
      font-weight: 600;
      font-size: 1.25rem;
      line-height: 1em;
      margin-top: .5em;
    }

    .s-profiles__profile__subtitle {
      font-weight: 500;
      font-size: 1.25rem;
      line-height: 1em;
    }

    .s-profiles__profile__description {
      margin-top: .5em;
      margin-bottom: .5em;
    }

    .s-profiles__profile__img {
      display: block;
      background: lightgray;
      width: 50%;
      aspect-ratio: 1/1;
      border-radius: 100%;
      margin: auto;
      object-fit: cover;
    }

    .s-profiles__profile__links {
      gap: .5rem;

      .with-img & {
        justify-content: center;
      }

      svg {
        display: block;
        height: 2rem;
        width: auto;

        &:hover {
          fill: var(--app-color--blue);
        }
      }
    }

  .s-profiles.style2 {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    grid-row-gap: 1rem;

    @media (max-width: scss-params.$fp-breakpoint-sm) {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    @media (max-width: scss-params.$fp-breakpoint-xs) {
      grid-template-columns: repeat(1, minmax(0, 1fr));
    }


    .s-profiles__profile__title {
      font-size: .85rem;
      line-height: 1em;
    }

    .s-profiles__profile__subtitle {
      font-size: .85rem;
      line-height: 1em;
    }

    .s-profiles__profile__description {
      font-size: .75rem;
      line-height: 1em;
    }

    .s-profiles__profile__img {
      width: 33%;
    }

    .s-profiles__profile__links {
      svg {
        height: 1.25rem;
        width: auto;
      }
    }
  }
</style>
