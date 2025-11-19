<script lang="ts">
    import type {IBlockImage, IImage} from "$lib/interfaces/cmsApiResponse.js";

    export let content: IBlockImage
    export let image: IImage[]
</script>


<div class="s-block-image"
     class:is-fixed={content.content.fixed === 'true'}
     style="background-image: url({image[0]?.resize.large}); background-position: {image[0]?.focus};"
>
    {#if (content.content.fixed === 'false')}
        <img
                class="s-block-image__img"
                src="{image[0]?.resize.large}"
                alt="illustration"
        />
    {/if}
</div>


<style lang="scss">
  @use "../../style/_scss-params";

  .s-block-image {
    max-width: 1000px;

    &.is-fixed {
      max-width: none;
      height: calc(100vh - var(--app-nav_height));
      width: 100%;
      background-attachment: fixed;
      background-size: cover;
      background-position: center;

      @media (max-width: scss-params.$fp-breakpoint-xs) {
        background-attachment: scroll;
      }
    }
  }

  .s-block-image__img {
    display: block;

    .is-fixed & {
      width: 100%;
      height: 100%;
      object-fit: cover;

    }
  }
</style>
