<script lang="ts">

    import {menuIsOpen, siteInfo} from "../../store";

</script>

<nav class="s-app-nav app-flex app-flex--justify_space-between app-flex--align_center">
    <div class="app-flex__basis-auto"
    >
        <a href="/"><img class="s-app-nav__logo" alt="retour à la home" src="/logo.svg"></a>
    </div>

    <div class="s-app-nav__buttons app-flex__basis-auto"
    >
        <div class="app-flex app-flex--gap_regular app-flex--align_center"
        >
            <a class="app-button app-button--rounded"
               style="
                    --app-button--color: var(--app-color--green);
                    --app-button--background-color: var(--app-color--blue);
                "
               href="/rendez-vous"
            >Prendre RDV</a>
            <div>social</div>
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
        <ul class="s-app-nav__list">
            {#each $siteInfo.nav as item}
                {#if item.showmenu}
                    <li><a
                            class="s-app-nav__list__item"
                            href="/{item.slug}"
                    >{item.title}</a></li>
                {/if}
            {/each}
        </ul>
    {/if}
</nav>

<style lang="scss" >
    .s-app-nav {
      height: var(--app-nav_height);
      padding: 1rem;
      box-sizing: border-box;
      flex-direction: row;
      justify-content: space-between;
    }

    .s-app-nav__logo {
      display: block;
      height: 1rem;
    }

    .s-app-nav__buttons {
      position: relative;
      z-index: 100;
    }

    .s-app-nav__list {
      border-radius: 2rem;
      background: white;
      border: solid var(--app-line-with) black;
      position: absolute;
      display: block;
      top: 0;
      right: var(--app-gutter_regular);
      width: 50%;
      padding: var(--app-nav_height) 2rem 2rem;
      line-height: 1.25em;
      font-size: 2rem;
      font-weight: 600;
    }

    .s-app-nav__list__item {
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
    }
</style>
