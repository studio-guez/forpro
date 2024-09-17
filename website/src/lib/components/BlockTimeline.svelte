<script lang="ts">
  import { onMount } from 'svelte'

  let timelineElement: HTMLElement | null = null

  onMount(() => {
    setTimelineInteractionObserver(timelineElement)
  })

  function setTimelineInteractionObserver(timelineElement: HTMLElement | null) {
    if (timelineElement === null) return

    const timelineItemInteractionObserver = new IntersectionObserver(
      entries => interactionObserverCallback(entries),
      {}
    )

    timelineElement.querySelectorAll('.v-time-line__item').forEach(timelineItem => {
      timelineItemInteractionObserver.observe(timelineItem)
    })
  }

  function interactionObserverCallback(entries: IntersectionObserverEntry[]) {
    entries.forEach(entry => {
      if (!(entry.target instanceof HTMLElement)) return
      if (entry.isIntersecting) {
        entry.target.classList.add('ts-is-intersecting')
      } else {
        entry.target.classList.remove('ts-is-intersecting')
      }
    })
  }
</script>

<section class="v-time-line" bind:this={timelineElement}>
  <div class="v-time-line__item">
    <h6 class="v-time-line__item__date">01.12.2023</h6>
    <h3 class="v-time-line__item__title">Ouverture des candidatures</h3>
    <p class="v-time-line__item__desc">Ouverture des postes – site internet et LinkedIn</p>
  </div>

  <div class="v-time-line__item">
    <h6 class="v-time-line__item__date">01.12.2023 au 10.1.2024</h6>
    <h3 class="v-time-line__item__title">Délais de postulation</h3>
    <p class="v-time-line__item__desc">
      <a class="app-button app-button--rounded" target="_blank" href="/documents/Comment_déposer_ma_candidature.pdf">
        Comment déposer ma&nbsp;candidature
      </a>
    </p>
  </div>

  <div class="v-time-line__item">
    <h6 class="v-time-line__item__date">19.1.2024</h6>
    <h3 class="v-time-line__item__title">Sélection 1<sup>er</sup> tour sur&nbsp;dossier</h3>
    <p class="v-time-line__item__desc">Mail d’annonce aux candidat·es sélectionné·es au 1er tour</p>
  </div>

  <div class="v-time-line__item">
    <h6 class="v-time-line__item__date">Samedi 10.2.2024</h6>
    <h3 class="v-time-line__item__title">1<sup>er</sup> tour, Atelier collectif</h3>
    <p class="v-time-line__item__desc">
      Atelier collectif de recrutement<br />
      Horaires : 8h30 à 13h
    </p>
    <p class="v-time-line__item__desc fp-text-small">
      Afin de vous plonger dans la logique de fonctionnement ForPro et de mettre en avant vos compétences personnelles et
      sociales, nous mettons en place une demi-journée d'ateliers collaboratifs. Dans ces ateliers, vous serez associé
      avec notre public – des jeunes de 16 à 20 ans – pour réaliser une tâche demandant de se coordonner et de se mettre
      d'accord. <br />Un temps de retour sur votre expérience de groupe est prévu en deuxième partie de matinée.
    </p>
  </div>

  <div class="v-time-line__item v-time-line__item--gant">
    <h6 class="v-time-line__item__date">13.2.2024 au 16.2.2024</h6>
    <h3 class="v-time-line__item__title">2<sup>ème</sup> tour FoodLab</h3>
    <p class="v-time-line__item__desc">Entretiens individuels</p>
  </div>

  <div class="v-time-line__item v-time-line__item--gant">
    <h6 class="v-time-line__item__date">26.2.2024</h6>
    <h3 class="v-time-line__item__title">Décisions FoodLab</h3>
    <p class="v-time-line__item__desc">Appels téléphoniques pour informer les candidat·es</p>
  </div>

  <div class="v-time-line__item v-time-line__item--pen">
    <h6 class="v-time-line__item__date">4.3.2024 au 8.3.2024</h6>
    <h3 class="v-time-line__item__title">2<sup>ème</sup> tour LearningLab</h3>
    <p class="v-time-line__item__desc">Entretiens individuels</p>
  </div>

  <div class="v-time-line__item v-time-line__item--pen">
    <h6 class="v-time-line__item__date">15.3.2024</h6>
    <h3 class="v-time-line__item__title">Décisions LearningLab</h3>
    <p class="v-time-line__item__desc">Appels téléphoniques pour informer les candidat·es</p>
  </div>

  <div class="v-time-line__item v-time-line__item--gant">
    <h6 class="v-time-line__item__date">1.6.2024 ou à convenir</h6>
    <h3 class="v-time-line__item__title">Entrée en fonction FoodLab</h3>
  </div>

  <div class="v-time-line__item v-time-line__item--pen">
    <h6 class="v-time-line__item__date">1.7.2024 ou à convenir</h6>
    <h3 class="v-time-line__item__title">Entrée en fonction LearningLab</h3>
  </div>
</section>

<style lang="scss">
  @use "../../style/_scss-params";

  .v-time-line {
    position: relative;
    display: block;
    width: min(70rem, 100%);

    &:before {
      content: '';
      position: absolute;
      width: 4px;
      height: 100%;
      background: var(--app-color--blue);
      top: 0;
      left: 50%;
      transform: translate(-50%, 0);

      @media (max-width: scss-params.$fp-breakpoint-sm) {
        left: .25rem;
      }
    }

    .v-time-line__item {
      position: relative;
      width: 50%;
      box-sizing: border-box;
      padding-right: 2rem;
      margin-top: 0;
      padding-bottom: 2rem;

      &:before {
        content: "";
        position: absolute;
        display: block;
        width: 1.5rem;
        height: 1.5rem;
        box-sizing: border-box;
        border: solid 3px var(--app-color--blue);
        border-radius: 100%;
        right: 0;
        top: 0;
        transform: translate(50%, -25%);

        //transition with InteractionObserver (see css below)
        transition: background-color 1s ease-in-out;
        background-color: white;
      }

      &:first-child {
        margin-top: 0;

      }

      &:nth-child(2n) {
        margin-left: 50%;
        padding-right: 0;
        padding-left: 2rem;

        &:before {
          right: auto;
          left: 0;
          top: 0;
          transform: translate(-50%, -25%);
        }
      }

      @media (max-width: scss-params.$fp-breakpoint-sm) {
        width: 100%;
        margin-left: 0 !important;
        padding-left: 1rem !important;
        padding-right: 0 !important;

        &:before {
          left: .75rem !important;
          width: 1rem !important;
          height: 1rem !important;
          transform: translate(-100%, 0) !important;
          background-image: none !important;
        }
      }

      .v-time-line__item__date {
        font-size: .85rem;
        color: var(--app-color--blue);
        padding-bottom: .25rem;

        //transition with InteractionObserver (see css below)
        transition: opacity .75s ease-in-out, transform .55s ease-in-out;
        transform: translateY(2rem);
        opacity: 0;
      }

      .v-time-line__item__title {
        margin: 0;
        text-align: left;

        //transition with InteractionObserver (see css below)
        transition: opacity .75s ease-in-out, transform .65s ease-in-out;
        transform: translateY(2rem);
        opacity: 0;
      }

      .v-time-line__item__desc {
        margin: 0;
        text-align: left;
        padding-top: 1rem;

        //transition with InteractionObserver (see css below)
        transition: opacity .75s ease-in-out, transform .75s ease-in-out;
        transform: translateY(2rem);
        opacity: 0;
      }

      //&.ts-is-intersecting {
      &:before {
        background-color: #ffcb8f;
      }

      .v-time-line__item__date {
        opacity: 1;
        transform: translateY(0);
      }

      .v-time-line__item__title {
        opacity: 1;
        transform: translateY(0);
      }

      .v-time-line__item__desc {
        opacity: 1;
        transform: translateY(0);
      }
      //}

      &.v-time-line__item--gant:global(.ts-is-intersecting) {
        &:before {
          border-width: 4px;
          width:  2.5rem;
          height: 2.5rem;
          background-image: url('/icons/gant.svg');
          background-size: 66% 66%;
          background-repeat: no-repeat;
          background-position: center;
        }
      }
      &.v-time-line__item--pen:global(.ts-is-intersecting) {
        &:before {
          border-width: 4px;
          width:  2.5rem;
          height: 2.5rem;
          background-image: url('/icons/pen.svg');
          background-size: 66% 66%;
          background-repeat: no-repeat;
          background-position: center;
        }
      }
    }
  }

  h3 {
    color: var(--app-color--pink);
  }
</style>
