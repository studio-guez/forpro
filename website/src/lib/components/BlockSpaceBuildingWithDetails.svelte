<script>
    import { writable } from 'svelte/store';

    let sectionIsActive_popup = writable(false);
    let sectionIsActive_MakerLab = writable(false);
    let sectionIsActive_LearningLab = writable(false);
    let sectionIsActive_GrandLab = writable(false);
    let sectionIsActive_Ecole_Horlogerie = writable(false);
    let sectionIsActive_Hotel_Entreprises = writable(false);
    let sectionIsActive_Creche = writable(false);
    let sectionIsActive_Foodlab = writable(false);

    const spaceBuildingElement = null;

    function clearAllActiveSections() {
        sectionIsActive_popup.set(false);
        sectionIsActive_MakerLab.set(false);
        sectionIsActive_LearningLab.set(false);
        sectionIsActive_GrandLab.set(false);
        sectionIsActive_Ecole_Horlogerie.set(false);
        sectionIsActive_Hotel_Entreprises.set(false);
        sectionIsActive_Creche.set(false);
        sectionIsActive_Foodlab.set(false);
    }

    function toggleActiveSection(sectionToToggle) {
        clearAllActiveSections();
        sectionToToggle.set(true);
    }
</script>

<section class="v-space-building">
    <div class="v-space-building__section-name">
        <div class:is-active="{sectionIsActive_popup}"
             on:mouseover={()   => sectionIsActive_popup.set(true)}
             on:mouseout={()    => () => sectionIsActive_popup.set(false)}
             on:click={()       => callbackWidthMDMaxWidth(() => goToPage('popup'), () => toggleActiveSection(sectionIsActive_popup))}
             class="fp-heading-h5 fp-text--without-margin is-Accueil-color">
            PoPup
        </div>
        <!-- Ajouter les autres div pour les différentes sections -->
    </div>
    <div class="v-space-building__sections">
        {#if $sectionIsActive_popup}
            <!-- Contenu de la section PoPup -->
        {/if}
        <!-- Ajouter d'autres sections conditionnelles ici -->
    </div>
</section>

<style lang="scss" >
  .is-Accueil-color {
    color: var(--fp-color-accueil);
  }

  .is-MakerLab-color              {color: var(--fp-color-makerlab);}
  .is-LearningLab-color           {color: var(--fp-color-learninglab);}
  .is-Foodlab-color               {color: var(--fp-color-foodlab);}
  .is-GrandLab-color              {color: var(--fp-color-grandlab);}
  .is-Hotel_Entreprises-color     {color: var(--fp-color-hotel_entreprises);}
  .is-Ecole_Horlogerie-color      {color: var(--fp-color-ecole_horlogerie);}
  .is-Creche-color                {color: var(--fp-color-creche);}


  [id^="Cache-"] {
    cursor: pointer;
    user-select: none !important;
  }

  svg {
    display: block;
  }

  .v-space-building {
    width: 100%;
    position: relative;
    display: flex;
    flex-direction: column;

    @container (min-width: 1800px) {
      display: block;
    }
  }

  .v-space-building__title {
    text-align: center;
    margin-left: auto;
    margin-right: auto;
  }

  .v-space-building__section-name {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    padding-top: 1rem;
    margin-bottom: 1rem;
    justify-content: center;
    user-select: none !important;
    cursor: pointer;

    > div {
      padding-right: var(--fp-gutter);
      padding-bottom: 1rem;
      padding-left: var(--fp-gutter);
      white-space: nowrap;

      &:not(.is-active) {
        color: var(--fp-color-black) !important;
      }
    }
  }

  .v-space-building__plan {
    position: relative;

    @container(min-width: 1000px) {
      margin-left:    -2.8rem;
      margin-right:   -2.8rem;
    }

    &.on-section-is-active {
      [id^='Section-']:not(.is-active) {
        * {
          fill: #e6e6e6 !important;
        }
      }
    }
  }

  .v-space-building__sections__description {
    background: rgba(255, 255, 255, 1);
    box-sizing: border-box;
    padding: 1rem;
    border: solid 3px;
    border-radius: 1rem;
    position: relative;
    margin-top: 1rem;

    > * {
      color: var(--fp-color-black);
    }

    @container (min-width: 1000px) {
      margin-top: 0;
      position: absolute;
      pointer-events: none;
      top: -1rem;

      .fp-ui-button {
        display: none;
      }
    }
  }

  .v-space-building__section-description--Accueil             {@extend .is-Accueil-color;}
  .v-space-building__section-description--MakerLab            {@extend .is-MakerLab-color;}
  .v-space-building__section-description--LearningLab         {@extend .is-LearningLab-color;}
  .v-space-building__section-description--Foodlab             {@extend .is-Foodlab-color;}
  .v-space-building__section-description--GrandLab            {@extend .is-GrandLab-color;}
  .v-space-building__section-description--Hotel_Entreprises   {@extend .is-Hotel_Entreprises-color;}
  .v-space-building__section-description--Ecole_Horlogerie    {@extend .is-Ecole_Horlogerie-color;}
  .v-space-building__section-description--Creche              {@extend .is-Creche-color;}



  .v-space-building__section-description--Accueil {
    > .fp-ui-button {
      border-color: var(--fp-color-accueil) !important;
    }

    @container (min-width: 1000px) {
      top: -1rem;
      right: calc( -100% / 18 * 2 );
      left: auto;
      width: 22rem;
    }
  }

  .v-space-building__section-description--MakerLab {
    .fp-ui-button {
      border-color: var(--fp-color-makerlab)
    }

    @container (min-width: 1000px) {
      top: -1rem;
      right: auto;
      left: calc( -100% / 18 * 2 );
      width: 22rem;
    }
  }

  .v-space-building__section-description--LearningLab {
    .fp-ui-button {
      border-color: var(--fp-color-learninglab)
    }

    @container (min-width: 1000px) {
      top: -1rem;
      right: calc( -100% / 18 * 2 );
      left: auto;
      width: 22rem;
    }
  }

  .v-space-building__section-description--Foodlab {
    .fp-ui-button {
      border-color: var(--fp-color-foodlab)
    }

    @container (min-width: 1000px) {
      top: 3rem;
      right: auto;
      left: calc( 100% / 18 * 2 );
      width: 22rem;
    }
  }

  .v-space-building__section-description--GrandLab {
    .fp-ui-button {
      border-color: var(--fp-color-grandlab)
    }

    @container (min-width: 1000px) {
      top: 1rem;
      right: calc( 100% / 18 * 2 );
      left: auto;
      width: 22rem;
    }
  }

  .v-space-building__section-description--Hotel_Entreprises {
    .fp-ui-button {
      border-color: var(--fp-color-hotel_entreprises)
    }

    @container (min-width: 1000px) {
      top: -1rem;
      right: auto;
      left: calc( -100% / 18 * 2 );
      width: 22rem;
    }
  }

  .v-space-building__section-description--Ecole_Horlogerie {
    .fp-ui-button {
      border-color: var(--fp-color-ecole_horlogerie)
    }

    @container (min-width: 1000px) {
      top: -1rem;
      right: auto;
      left: calc( -100% / 18 * 2 );
      width: 22rem;
    }
  }

  .v-space-building__section-description--Creche {
    .fp-ui-button {
      border-color: var(--fp-color-creche)
    }

    @container (min-width: 1000px) {
      top: -1rem;
      right: auto;
      left: calc( -100% / 18 * 2 );
      width: 22rem;
    }
  }

</style>
