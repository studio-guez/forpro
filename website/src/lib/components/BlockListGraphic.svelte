<script lang="ts">
    import type {IBlockListGraphic} from "$lib/interfaces/cmsApiResponse";

  export let data: IBlockListGraphic

</script>

<section class="s-block-list-graphic">
  <div class="s-block-list-graphic__content">
    {#each data.content.items as item}
      <div class="s-block-list-graphic__content__item">{item.title}</div>
    {/each}
  </div>
</section>

<style lang="scss">
  @use "../../style/_scss-params";

  .s-block-list-graphic {
    width: 100%;
    display: flex;
    justify-content: center;
    padding-top: 5rem;
  }

  .s-block-list-graphic__content {
    display: flex;
    width: 100%;
    max-width: 30rem;
    flex-direction: column;
    align-items: center;
  }

  .s-block-list-graphic__content__item {
    background: var(--app-color--orange);
    color: var(--app-color--blue);
    padding: .5rem;
    width: 100%;
    text-align: center;
    position: relative;
    //margin-top: -.5rem;

    &:before, &:after {
      content: '';
      display: block;
      position: absolute;
      background: var(--app-color--orange);
      width: 100%;
      height: 1rem;
    }

    &:nth-child(2n) {
      background: var(--app-color--blue);
      color: var(--app-color--orange);

      &:before, &:after {
        background: var(--app-color--blue);
      }
    }


    //1
    &:nth-child(1n) {
      left: -50%;
      transform: translate(75%) rotate(-10deg);
      max-width: 10rem;
      padding-top: 0rem;
      padding-bottom: 2rem;

      &:before {
        width:calc(100% + 2rem);
        top: 0;
        left: 50%;
        transform: translate(-50%, -90%);
      }
      &:after {
        width:calc(100% + 2rem);
        top: 100%;
        left: 50%;
        transform: translate(-50%, -90%);
      }
    }


    //2
    &:nth-child(2n) {
      left: 0%;
      transform: translate(0%, 0%) rotate(8deg);
      max-width: 8.5rem;
      padding-top: 1rem;
      padding-bottom: 2rem;

      &:before {
        width: 100%;
        top: 0;
        left: 50%;
        transform: translate(-50%, -90%);
        border-top-left-radius: 2rem;
        border-top-right-radius: 2rem;

      }
      &:after {
        width:calc(100% + 2rem);
        top: 100%;
        left: 50%;
        transform: translate(-50%, -90%);
      }
    }







    // 3
    &:nth-child(3n) {
      left: 50%;
      transform: translate(-75%, 0%) rotate(-10deg);
      max-width: 10rem;


      padding-top: 1rem;
      padding-bottom: 2rem;

      &:before {
        width: 100%;
        top: 0;
        left: 50%;
        transform: translate(-50%, -90%);
        border-top-left-radius: 2rem;
        border-top-right-radius: 2rem;

      }
      &:after {
        width:100%;
        top: 100%;
        left: 50%;
        transform: translate(-50%, -10%);
        border-bottom-left-radius: 2rem;
        border-bottom-right-radius: 2rem;
      }
    }






    &:nth-child(4n) {
      left: 0%;
      transform: translate(-10%, 0%) rotate(15deg);
      max-width: 10rem;
    }
    &:nth-child(5n) {
      left: -50%;
      transform: translate(50%) rotate(-10deg);
      max-width: 10rem;
    }
    &:nth-child(6n) {
      left: 0%;
      transform: translate(10%, -15%) rotate(5deg);
      max-width: 10rem;
    }
    &:nth-child(7n) {
      left: 50%;
      transform: translate(-50%, 0%) rotate(-5deg);
      max-width: 10rem;
    }
    &:nth-child(8n) {
      left: 0%;
      transform: translate(0%, 0%) rotate(-15deg);
      max-width: 10rem;
    }
    &:nth-child(9n) {
      left: 0%;
      transform: translate(-25%, 10%) rotate(5deg);
      max-width: 10rem;
    }
    &:nth-child(10n) {
      left: 0%;
      transform: translate(15%, 20%) rotate(-2deg);
      max-width: 10rem;
    }




  }

</style>
