<script lang="ts">
import {siteInfo} from "../../store";
</script>

<footer class="s-footer app-flex"
>
    <div class="app-grid--column-2 app-width-gutter app-grid--gap_regular s-footer__text">
        <div class="app-typo_text-content">
            {@html $siteInfo.footer}
        </div>
        <div class="app-grid--column-2">
            <div>
                {#each $siteInfo.nav as item}
                    {#if item.showmenu}
                        <div><a
                                class="s-app-nav__list__item"
                                href="/{item.slug}"
                        >{item.title}</a></div>
                    {/if}
                {/each}
            </div>
            <div>
                <div><a
                        class="s-app-nav__list__item"
                        href="#"
                >Linkedin</a></div>
                <div><a
                        class="s-app-nav__list__item"
                        href="#"
                >Facebook</a></div>
                <div><a
                        class="s-app-nav__list__item"
                        href="#"
                >Instagram</a></div>
            </div>
        </div>
    </div>
    <div class="app-flex__basis-1-1">
        <img class="s-footer__img"
                alt="logo fix dans le footer, simplment pour le visuel"
                src="/logo.svg"/>
    </div>
</footer>

<style lang="scss">
    .s-footer {
      width: 100%;
      box-sizing: border-box;
      background: var(--app-color-beige);
      overflow: hidden;
      margin-top: 5rem;
    }

    .s-footer__text {
       padding-top: 1rem;
      padding-bottom: 1rem;
    }

    .s-footer__img {
      display: block;
      width: 90%;
      bottom: 0;
      margin: 1rem auto;
    }
</style>
