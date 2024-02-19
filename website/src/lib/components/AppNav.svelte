<script lang="ts">
    import {menuIsOpen, siteInfo} from "../../store";
</script>

<nav class="s-app-nav app-flex app-flex--justify_space-between app-flex--align_center">
    <div class="app-flex__basis-auto s-app-nav__logo--box">
        <a href="/"><img class="s-app-nav__logo" alt="retour à la home" src="/logo.svg"></a>
    </div>

    <div class="s-app-nav__buttons app-flex__basis-auto">
        <div class="s-app-nav__buttons__container app-flex app-flex--gap_regular app-flex--align_center">
            <a class="app-button app-button--rounded"
               style="
                    --app-button--color: var(--app-color--green);
                    --app-button--background-color: var(--app-color--blue);
                "
               href="/rendez-vous"
            >Prendre RDV</a>
            <div class="s-app-nav__social-icon">
                <a target="_blank" href="https://www.linkedin.com/company/fondation-forpro/ "   ><img src="/social-1.svg" alt="social link" ></a>
                <a target="_blank" href="https://www.facebook.com/forpro.ge"                    ><img src="/social-2.svg" alt="social link" ></a>
                <a target="_blank" href="https://www.instagram.com/forpro_ge/"                  ><img src="/social-3.svg" alt="social link" ></a>
<!--                <img src="/social-4.svg" alt="social link" >-->
<!--                <img src="/social-5.svg" alt="social link" >-->
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
            </ul>
        </div>
    {/if}
</nav>

<style lang="scss" >
  @use '../../style/_scss-params';

    .s-app-nav {
      height: var(--app-nav_height);
      padding: var(--app-flex--gap_half);
      box-sizing: border-box;
      flex-direction: row;
      justify-content: space-between;
    }

    .s-app-nav__social-icon {
      background: var(--app-color--blue);
      display: flex;
      padding: .25rem 1rem;
      gap: .5rem;
      border-radius: 1rem;
      border: solid 2px var(--app-color--blue);

      a {
        display: block;
      }

      img {
        display: block;
        height: 1.5rem;
      }
    }

    .s-app-nav__logo--box {
      background: white;
      padding: .5rem 1rem;
      border-radius: 2rem;
      z-index: 100;
    }

    .s-app-nav__logo {
      display: block;
      height: 1.15rem;
    }

    .s-app-nav__buttons {
      position: relative;
      z-index: 100;

      @media (max-width: scss-params.$fp-breakpoint-sm) {
        width: 100%;
      }
    }

    .s-app-nav__buttons__container {
      @media (max-width: scss-params.$fp-breakpoint-sm) {
        margin-top: var(--app-flex--gap_half);
        justify-content: flex-end;
      }
    }

    .s-app-nav__list-container {
      --position: 5px;
      position: fixed;
      top: var(--position);
      right: var(--position);
      width: calc(50% - var(--position) );
      height: calc(100% - var(--position) * 2 );
      background: white;
      border: solid var(--app-line-with) black;
      padding: var(--app-nav_height) .15rem 2rem 2rem;
      border-radius: 1.3rem;

      @media (max-width: scss-params.$fp-breakpoint-sm) {
        width: calc(100% - var(--position) - var(--position) );
      }
    }

    .s-app-nav__list {
      padding-right: .5rem;
      height: 100%;
      overflow: auto;
      display: block;
      line-height: 1.25em;
      font-size: 2rem;
      font-weight: 600;


      @media (max-width: scss-params.$fp-breakpoint-xs) {
        font-size: 1.5rem;
      }
    }

    .s-app-nav__list__item {
      @media (max-width: scss-params.$fp-breakpoint-sm) {
        margin-bottom: .25em;
      }

      &.is-subpage {
        font-size: .66em;
        line-height: 1em;

        + .s-app-nav__list__item:not(.is-subpage) {
            margin-top: .25em;
        }
      }



      &:hover {
        color: var(--app-color--blue)
      }
    }

    .s-app-nav__icon-menu {
      background: var(--app-color--green);
      height: 2rem;
      width: 2rem;
      border-radius: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;

      @media (max-width: scss-params.$fp-breakpoint-sm) {
        position: fixed;
        top: var(--app-flex--gap_half);
        right: var(--app-flex--gap_half);
      }
    }
</style>
