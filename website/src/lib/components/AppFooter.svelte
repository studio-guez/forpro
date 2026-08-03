<script lang="ts">
import {siteInfo} from "../../store";
</script>

<footer class="s-footer app-flex"
>
    <div class="app-grid--column-2 app-grid--xs--column-1 app-width-gutter app-grid--gap_regular s-footer__text">

        <div class="app-flex">
            <div class="app-grid--column-2 app-grid--xs--column-1"
                 style="gap: 2rem"
            >
                <div class="app-typo_text-content">
                    {@html $siteInfo.footer}
                </div>
                <div>
                    <img src="/vignette_sbfi-v2.png"
                         alt="logo SBFI"
                         style="display: block; width: 10rem"
                    >
                </div>
            </div>
        </div>

        <div class="app-grid--column-2 app-grid--sm--column-1">
            <div>
                {#each $siteInfo.nav as item}
                    {#if item.showmenu}
                        <div
                                class="s-app-footer__list__item"
                                class:is-subpage={item.title.startsWith('->')}
                        ><a
                                href="/{item.slug}"
                        >{item.title.replace(/^->/, '→')}</a></div>
                    {/if}
                {/each}
            </div>
            <div>
                <div
                        class="s-app-footer__list__item"
                ><a
                        href="https://www.linkedin.com/company/fondation-forpro/ "
                        target="_blank"
                >LinkedIn</a></div>
                <div
                        class="s-app-footer__list__item"
                ><a
                        href="https://www.facebook.com/forpro.ge"
                        target="_blank"
                >Facebook</a></div>
                <div
                        class="s-app-footer__list__item"
                ><a
                        href="https://www.instagram.com/forpro_ge/"
                        target="_blank"
                >Instagram</a></div>
                <div
                        class="s-app-footer__list__item"
                ><a
                        href="https://t.snapchat.com/sIs5mQiQ"
                        target="_blank"
                >Snapchat</a></div>
                <div
                        class="s-app-footer__list__item"
                ><a
                        href="https://www.tiktok.com/@_forpro/"
                        target="_blank"
                >TikTok</a></div>

                <div class="s-app-footer__list__item"
                     style="margin-top: 1rem"
                >
                    <a href="/credits" >Crédits du site</a> |
                    <a href="/politique-de-confidentialite">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </div>
    <div class="app-flex app-flex--justify_end app-width-100 app-flex--gap_regular app-width-gutter">
    </div>
    <div class="app-flex__basis-1-1">
        <img class="s-footer__img"
                alt="logo fix dans le footer, simplment pour le visuel"
                src="/logo.svg"/>
    </div>
</footer>

