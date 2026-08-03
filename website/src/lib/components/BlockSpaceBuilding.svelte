<section class="s-block-space-build--nav" on:mouseout={() => (currentOverSection = "")} on:blur={() => (currentOverSection = "")} role="presentation">
  <div class="s-block-space-build--nav__title">
    <div class="s-block-space-build--nav__title__value" >
      {currentOverSectionName}
    </div>
  </div>
  <svg
          id="Layer_2"
          data-name="Layer 2"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 1200 478"
  >
    <g>
      <g>
        <rect  class:is-over="{currentOverSection === 'creche'}"      on:mouseover="{() => currentOverSection = 'creche'}" on:focus="{() => currentOverSection = 'creche'}" role="button" tabindex="0"              class="s-block-space-build--nav__svg-building-link Creche" x="975" y="0" width="166" height="69" style="fill: var(--fp-color-creche);"/>
        <rect  class:is-over="{currentOverSection === 'horlogerie'}"  on:mouseover="{() => currentOverSection = 'horlogerie'}" on:focus="{() => currentOverSection = 'horlogerie'}" role="button" tabindex="0"          class="s-block-space-build--nav__svg-building-link EcoleHorlogerie" x="225" y="390" width="750" height="88" style="fill: var(--fp-color-ecole_horlogerie);"/>
        <rect  class:is-over="{currentOverSection === 'horlogerie'}"  on:mouseover="{() => currentOverSection = 'horlogerie'}" on:focus="{() => currentOverSection = 'horlogerie'}" role="button" tabindex="0"          class="s-block-space-build--nav__svg-building-link EcoleHorlogerie" y="132" width="1200" height="128" style="fill: var(--fp-color-ecole_horlogerie);"/>
        <rect  class:is-over="{currentOverSection === 'FactoryLab'}"  on:mouseover="{() => currentOverSection = 'FactoryLab'}" on:focus="{() => currentOverSection = 'FactoryLab'}" role="button" tabindex="0"          class="s-block-space-build--nav__svg-building-link HotelEntreprise" y="61" width="1200" height="71" style="fill: var(--fp-color-hotel_entreprises);"/>
        <rect  class:is-over="{currentOverSection === 'FactoryLab'}"  on:mouseover="{() => currentOverSection = 'FactoryLab'}" on:focus="{() => currentOverSection = 'FactoryLab'}" role="button" tabindex="0"          class="s-block-space-build--nav__svg-building-link HotelEntreprise" x="975" y="390" width="225" height="88" style="fill: var(--fp-color-hotel_entreprises);"/>
        <rect  class:is-over="{currentOverSection === 'foodlab'}"     on:mouseover="{() => currentOverSection = 'foodlab'}" on:focus="{() => currentOverSection = 'foodlab'}" role="button" tabindex="0"             class="s-block-space-build--nav__svg-building-link FoodLab" x="70.0027" width="905.03136" height="69" style="fill: var(--fp-color-foodlab);"/>
        <rect  class:is-over="{currentOverSection === 'makerlab'}"    on:mouseover="{() => currentOverSection = 'makerlab'}" on:focus="{() => currentOverSection = 'makerlab'}" role="button" tabindex="0"            class="s-block-space-build--nav__svg-building-link MakerLab" x="975" y="260" width="225" height="130" style="fill: var(--fp-color-makerlab);"/>
        <rect  class:is-over="{currentOverSection === 'learninglab'}" on:mouseover="{() => currentOverSection = 'learninglab'}" on:focus="{() => currentOverSection = 'learninglab'}" role="button" tabindex="0"         class="s-block-space-build--nav__svg-building-link LearningLab" x="225" y="260" width="750" height="130" style="fill: var(--fp-color-learninglab);"/>
        <rect  class:is-over="{currentOverSection === 'grandlab'}"    on:mouseover="{() => currentOverSection = 'grandlab'}" on:focus="{() => currentOverSection = 'grandlab'}" role="button" tabindex="0"            class="s-block-space-build--nav__svg-building-link GrandLab" y="260" width="225" height="130" style="fill: var(--fp-color-grandlab);"/>
        <rect  class:is-over="{currentOverSection === 'popup'}"       on:mouseover="{() => currentOverSection = 'popup'}" on:focus="{() => currentOverSection = 'popup'}" role="button" tabindex="0"               class="s-block-space-build--nav__svg-building-link Accueil" y="390" width="225" height="88" style="fill: var(--fp-color-accueil);"/>

        <g
                style="pointer-events: none"
        >
          <path d="m1192,69v401H8V69h1184m8-8H0v417h1200V61h0Z" style="fill: #222221;"/>
          <path d="m1133,8v53H78V8h1055M1141,0H70v69h1071V0h0Z" style="fill: #222221;"/>
          <line y1="132" x2="1200" y2="132" style="fill: none; stroke: #222221; stroke-miterlimit: 10; stroke-width: 8px;"/>
          <line y1="196" x2="1200" y2="196" style="fill: none; stroke: #222221; stroke-miterlimit: 10; stroke-width: 8px;"/>
          <line y1="260" x2="1200" y2="260" style="fill: none; stroke: #222221; stroke-miterlimit: 10; stroke-width: 8px;"/>
          <line y1="390" x2="1200" y2="390" style="fill: none; stroke: #222221; stroke-miterlimit: 10; stroke-width: 8px;"/>
          <line x1="225" y1="326" x2="975" y2="326" style="fill: none; stroke: #222221; stroke-miterlimit: 10; stroke-width: 8px;"/>
          <line x1="975" y1="260" x2="975" y2="478" style="fill: none; stroke: #222221; stroke-miterlimit: 10; stroke-width: 8px;"/>
          <line x1="225" y1="260" x2="225" y2="478" style="fill: none; stroke: #222221; stroke-miterlimit: 10; stroke-width: 8px;"/>
          <line x1="975" y1="0" x2="975" y2="69" style="fill: none; stroke: #222221; stroke-miterlimit: 10; stroke-width: 8px;"/>
        </g>
      </g>
    </g>
  </svg>
</section>

<script lang="ts">

  let currentOverSection = "";

  // todo: add this content in Kirby Admin
  let sectionsDetails = [
    {slug: 'creche',      title: {value: 'Crèche'}},
    {slug: 'horlogerie',  title: {value: "École d'horlogerie"}},
    {slug: 'FactoryLab',  title: {value: 'FactoryLab'}},
    {slug: 'foodlab',     title: {value: 'FoodLab'}},
    {slug: 'makerlab',    title: {value: 'MakerLab'}},
    {slug: 'learninglab', title: {value: 'LearningLab'}},
    {slug: 'grandlab',    title: {value: 'GrandLab'}},
    {slug: 'popup',       title: {value: 'Popup'}},
  ];

  function getSectionNameBySlug(slug: string) {
    const section = sectionsDetails.find((section) => section.slug === slug);
    return section ? section.title.value : "";
  }

  $:currentOverSectionName = getSectionNameBySlug(currentOverSection);
</script>

<style lang="scss">
  .s-block-space-build--nav {
    position: relative;
    max-width: 40rem;
  }

  .s-block-space-build--nav__title {
    height: 2rem;
    position: relative;
  }

  .s-block-space-build--nav__title__value {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    font-size: 1rem;
    line-height: 1rem;
    text-align: center;
    white-space: nowrap;
    font-weight: 500;
  }

  svg rect:not(.is-over) {
    fill: white !important;
  }

  .s-block-space-build--nav__svg-building-link {
    cursor: pointer;
  }
</style>
