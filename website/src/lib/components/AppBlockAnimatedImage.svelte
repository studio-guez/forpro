<div class="s-block-animated-image">
  <img src="/block_animated_images/village_forpro.png" alt="logo" />
  <img class="s-block-animated-image__img" src="/block_animated_images/MakerLab.png" alt="logo" />
  <img class="s-block-animated-image__img" src="/block_animated_images/LearningLab.png" alt="logo" />
  <img class="s-block-animated-image__img" src="/block_animated_images/FoodLab.png" alt="logo" />
  <img class="s-block-animated-image__img" src="/block_animated_images/GrandLab.png" alt="logo" />
</div>

<style lang="scss">
  .s-block-animated-image {
    position: relative;
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

</style>
