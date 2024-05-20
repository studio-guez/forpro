<div class="s-page-changer-de-regard"
>
  <div class="s-page-changer-de-regard__container">
    <div class="s-page-changer-de-regard__container__img"></div>
    <div class="s-page-changer-de-regard__container__content">
      <h1><span class="s-page-changer-de-regard__title__big">10 raisons</span>
          <br>de changer de point de vue sur l’apprentissage</h1>
      <ul class="app-list-fp-style">
        <li>Le système d'apprentissage en Suisse est hautement valorisé pour son modèle dual. Unique, il combine enseignement théorique en école professionnelle et expérience pratique en entreprise. Il répond aux exigences actuelles des entreprises et renforce l'employabilité des apprenti·e·s sur le marché du travail&nbsp;: c’est un véritable passeport pour l’emploi.</li>
        <li>Avec plus de 240 formations disponibles, l'apprentissage couvre tous les secteurs d'activité, offrant une diversité d’options adaptées à différentes passions et intérêts.</li>
        <li>Tout sauf une impasse, l'apprentissage permet d’accéder à l'enseignement supérieur via la maturité professionnelle, permettant la poursuite d'études dans les hautes écoles spécialisées, les universités et les écoles polytechniques fédérales.</li>
        <li>L'apprentissage permet aux jeunes de bénéficier d'une rémunération dès le début de leur formation, avec des perspectives salariales souvent plus favorables que celles offertes par la formation générale.</li>
        <li>La formation professionnelle permet d’obtenir un diplôme donnant accès au marché du travail sans nécessiter de longues années d'études. Un CFC, c’est trois ans d’études et un métier en main&nbsp;! Et si on choisit de poursuivre ses études, à chaque étape, on peut opter pour l’emploi.</li>
        <li>L'apprentissage offre aux jeunes la possibilité de transformer leur passion en carrière professionnelle, leur permettant d'exercer un métier qu'ils aiment. De plus, celles et ceux qui ont opté pour cette voie après le Cycle d'orientation expriment une satisfaction sensiblement supérieure à celle des jeunes ayant choisi une filière généraliste.</li>
        <li>L'apprentissage permet aux apprenti∙e∙s d'appliquer immédiatement les connaissances acquises et d'évoluer rapidement dans leur domaine.</li>
        <li>Le choix d’un apprentissage après le Cycle d’orientation n’entrave en rien la possibilité d’une réorientation professionnelle par la suite et permet une mobilité professionnelle aussi grande qu’avec un diplôme d’une haute école.</li>
        <li>Créer sa propre entreprise devient une option concrète après l'obtention d'une AFP ou d'un CFC. L'entrepreneuriat est facilité par les années d’expérience pratique accumulées durant la formation.</li>
        <li>Le système d'apprentissage suisse est reconnu et envié mondialement, et peut ouvrir les portes à des opportunités de carrière à l’étranger.</li>
      </ul>
    </div>
  </div>
  <div class="s-page-changer-de-regard__footer">
    <div>
      <button class="app-button app-button--rounded"
              style="
                --app-button--color: black;
                --app-button--background-color: var(--app-color--pink);
                --app-button--border-color: var(--app-color--pink);
              "
      >En savoir +</button>
    </div>
  </div>
</div>

<style lang="scss">
  $page-change-de-regard--breakpoint: 800px;

  .s-page-changer-de-regard {
    --page-changer-de-regard--color-red: #F2030D;
    --page-changer-de-regard--color-purple: #7833A1;

    --page-changer-de-regard--color-purple--dark: #37007D;
    --page-changer-de-regard--color-yellow: #FFF700;

    --page-changer-de-regard--color-blue--saturat: #0000FF;
    --page-changer-de-regard--color-orange: #FF6917;

    --page-changer-de-regard--color-green: #009185;
    --page-changer-de-regard--color-pink: #F5008C;

    --page-changer-de-regard--color-blue: #1754FF;
    --page-changer-de-regard--color-pink--saturate: #FF00FC;



    h1 {
      color: var(--page-changer-de-regard--color-orange);
      margin-bottom: 3rem;
      font-size: 1.25rem;
      line-height: 1.25em;

      > span {
        font-size: 2.5em;
      }

      @media (min-width: 450px) {
        > span {
          font-size: 2em;
        }
        font-size: 1.5rem;
      }

      @media (min-width: 600px) {
        font-size: 1.75rem;
      }
    }

    background: black;
    color: white;
  }

  .s-page-changer-de-regard__container {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-content: flex-start;

    @media (min-width: $page-change-de-regard--breakpoint) {
      flex-direction: row;
    }
  }

  .s-page-changer-de-regard__container__img {
    width: 100%;
    height: 50vh;
    background: url('/changerderegard/landing-desktop.jpeg') no-repeat 0 33%;
    background-size: cover;
    position: relative;

    @media (min-width: $page-change-de-regard--breakpoint) {
      width: calc(100% / 12 * 5);
      height: 100vh;
      position: sticky;
      top: calc( -1 * var(--app-nav_height));
    }

    &:before {
      content: '';
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 2rem;
      background: linear-gradient(to top, rgba(0,0,0,1), rgba(0,0,0,0));
    }
  }

  .s-page-changer-de-regard__container__content {
    box-sizing: border-box;
    width: 100%;
    padding: 1rem;

    @media (min-width: $page-change-de-regard--breakpoint) {
      padding-left: 4rem;
    }
  }

  .s-page-changer-de-regard__footer {
    position: sticky;
    bottom: 0;
    display: flex;
    box-sizing: border-box;
    background: white;
    color: black;
    flex-direction: row;
    justify-content: center;
    padding-top: .5rem;
    padding-bottom: .5rem;
  }
</style>
