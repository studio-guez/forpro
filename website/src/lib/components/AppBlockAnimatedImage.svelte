<div class="s-block-animated-image">

  <div class="s-block-animated-image__informations">
    <div class="s-block-animated-image__informations__icons">
      <div class="s-block-animated-image__items"><img src="/block_animated_images/icons/A01.svg" alt="icons" /><div>Expo</div></div>
      <div class="s-block-animated-image__items"><img src="/block_animated_images/icons/A02.svg" alt="icons" /><div>Départ visites</div></div>
      <div class="s-block-animated-image__items"><img src="/block_animated_images/icons/A03.svg" alt="icons" /><div>Concert</div></div>
      <div class="s-block-animated-image__items"><img src="/block_animated_images/icons/A04.svg" alt="icons" /><div>Salle panoramique</div></div>
      <div class="s-block-animated-image__items"><img src="/block_animated_images/icons/A05.svg" alt="icons" /><div>Photobooth</div></div>
      <div class="s-block-animated-image__items"><img src="/block_animated_images/icons/A06.svg" alt="icons" /><div>Sculpture du Maker</div></div>
    </div>

    <div class="s-block-animated-image__informations__icons">
      <div class="s-block-animated-image__items"><img src="/block_animated_images/icons/B01.svg" alt="icons" /><div>Informations</div></div>
      <div class="s-block-animated-image__items"><img src="/block_animated_images/icons/B02.svg" alt="icons" /><div>Pause café</div></div>
      <div class="s-block-animated-image__items"><img src="/block_animated_images/icons/B03.svg" alt="icons" /><div>Foodcourt : 11h30–13h30</div></div>
    </div>
  </div>

  <div class="s-block-animated-image__images">
    <img src="/block_animated_images/village_forpro.png" alt="logo" />
    <img class="s-block-animated-image__img" src="/block_animated_images/MakerLab.png" alt="logo" />
    <img class="s-block-animated-image__img" src="/block_animated_images/LearningLab.png" alt="logo" />
    <img class="s-block-animated-image__img" src="/block_animated_images/FoodLab.png" alt="logo" />
    <img class="s-block-animated-image__img" src="/block_animated_images/GrandLab.png" alt="logo" />
  </div>

</div>

<style lang="scss">
  @use '../../style/_scss-params';

  .s-block-animated-image__images {
    position: relative;
    overflow: hidden;
  }

  .s-block-animated-image__img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: auto;
  }

  img {
    $animaation-duration: 8s;

    width: 100%;
    height: auto;
    max-height: 90vh;
    object-fit: contain;
    animation: float-animation-x ease-in-out infinite;

    &:nth-child(1) {
      animation-delay: 0s;
      animation-duration: $animaation-duration + 0.0;
    }

    &:nth-child(2) {
      animation-name: float-animation-y;
      animation-delay: .25s;
      animation-duration: $animaation-duration + 0.5;
    }

    &:nth-child(3) {
      animation-name: float-animation-y;
      animation-delay: .5s;
      animation-duration: $animaation-duration + 1.0;
    }

    &:nth-child(4) {
      animation-delay: .75s;
      animation-duration: $animaation-duration - 0.5;
    }

    &:nth-child(5) {
      animation-delay: 1s;
      animation-duration: $animaation-duration - 1.0;
    }
  }

  @keyframes  float-animation-y {
    0% {
      transform: translate3d(0, .75%, 0);
    }

    50% {
      transform: translate3d(0%, -.75%, 0);
    }

    100% {
      transform: translate3d(0%, .75%, 0);
    }
  }


  @keyframes  float-animation-x {
    0% {
      transform: translate3d(.75%, 0, 0);
    }

    50% {
      transform: translate3d(-.75%, 0%, 0);
    }

    100% {
      transform: translate3d(.75%, 0%, 0);
    }
  }


  .s-block-animated-image__informations {
    display: flex;
    gap: var(--app-gutter_regular);
    justify-content: center;
    padding-bottom: var(--app-gutter_regular);
    padding-left: var(--app-flex--gap_half);
    padding-right: var(--app-flex--gap_half);

    @media (max-width: scss-params.$fp-breakpoint-xs) {
      flex-wrap: wrap;
      justify-content: flex-start;
    }
  }

  .s-block-animated-image__items {
    display: flex;
    gap: var(--app-flex--gap_half);

    @media (max-width: scss-params.$fp-breakpoint-sm) {
      font-size: .75rem;
    }


    img {
      display: block;
      width: 1rem;
    }
  }

  .s-block-animated-image__informations__icons {
    display: flex;
    gap: var(--app-flex--gap_half);
    flex-direction: column;
  }
</style>
