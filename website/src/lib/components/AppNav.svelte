<script lang="ts">
    import {menuIsOpen, siteInfo} from "../../store";
</script>

<nav class="s-app-nav app-flex app-flex--justify_space-between app-flex--align_center">
    <div class="app-flex__basis-auto s-app-nav__logo--box">
        <a href="/"><img class="s-app-nav__logo" alt="retour à la home" src="/logo.svg"></a>
    </div>

    <div class="s-app-nav__buttons app-flex__basis-auto">
        <div class="s-app-nav__buttons__container app-flex app-flex--gap_regular app-flex--align_center">
            <div class="s-app-nav__social-icon">
                <a target="_blank" href="https://www.linkedin.com/company/fondation-forpro/ "   ><img src="/social-1.svg" alt="social link" ></a>
                <a target="_blank" href="https://www.facebook.com/forpro.ge"                    ><img src="/social-2.svg" alt="social link" ></a>
                <a target="_blank" href="https://www.instagram.com/forpro_ge/"                  ><img src="/social-3.svg" alt="social link" ></a>
                <a target="_blank" href=https://t.snapchat.com/sIs5mQiQ                         ><img src="/social-4.svg" alt="social link" ></a>
                <a target="_blank" href="https://www.tiktok.com/@_forpro"                       ><img src="/social-5.svg" alt="social link" ></a>
            </div>
            <button class="s-app-nav__icon-menu"
                    on:click={() => menuIsOpen.set(!$menuIsOpen)}>
                {#if ($menuIsOpen)}
                    <img class="s-app-nav__icon-menu__img" alt="close menu" src="/close_FILL0_wght400_GRAD0_opsz24.svg" >
                {:else }
                    <img class="s-app-nav__icon-menu__img" alt="open menu" src="/menu_FILL0_wght400_GRAD0_opsz24.svg" >
                {/if}
            </button>
        </div>
    </div>

    {#if ($menuIsOpen)}
        <div class="s-app-nav__list-container">
            <ul class="s-app-nav__list">
                {#each $siteInfo.nav as item}
                    {#if item.showmenu}
                        <li
                            class="s-app-nav__list__item"
                            class:is-subpage={item.title.startsWith('->')}
                        ><a
                                href="/{item.slug}"
                        >{item.title.replace(/^->/, '')}</a></li>
                    {/if}
                {/each}
                <li class="s-app-nav__list__item"
                ><a
                        href="/changerderegard"
                >Changer de regard sur l'apprentissage</a></li>
            </ul>
        </div>
    {/if}
</nav>

