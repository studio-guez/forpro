<script lang="ts">
    import type {ICta, IImage} from "$lib/interfaces/cmsApiResponse";

    export let content: ICta;
    export let image: IImage[];

</script>

<div class="s-block-cta app-flex app-flex--justify_center">
    <a class="s-block-cta__button {content.content.styles}"
       class:has-icon={image.length > 0}
       href="{content.content.link}"
       style="
            --s-cat-background-color: {content.content.backgroundcolor};
            --s-cat-color: {content.content.textcolor};
        "
    >
        {#if (image.length > 0)}
            <img class="s-block-cta__icon"
                 src={image[0].url}
                 alt="icon illustratif pour le bouton"/>
        {/if}
        <div>
            {content.content.text}
        </div>
    </a>
</div>


<style lang="scss">
    .s-block-cta__button {
      background: var(--s-cat-background-color);
      color: var(--s-cat-color);
      box-sizing: border-box;
      text-decoration: none;
      text-align: center;
      display: inline-block;
      white-space: nowrap;
      position: fixed;
      bottom: 0;
      left: 0;
      z-index: 5;
      font-size: max(1rem, 2vw);
      line-height: 1em;

      &.style1 {
        width: 100%;

        > * {
          padding-top: .5rem;
          padding-bottom: .5rem;
            animation: scroll-animation 50s linear infinite;
        }
      }

      &.style2 {
        font-weight: 600;
        font-size: max(1rem, 2vw);
        align-items: center;
        padding: max(1rem, 2vw) max(2rem, 4vw);
        border-radius: 10em;
        position: relative;
        z-index: 0;
      }

      &.has-icon {
        background: transparent;
        line-height: 1em;
        font-size: 2rem;
      }
    }

    .s-block-cta__icon {
      display: block;
      height: 8rem;
      margin: auto;
    }

    @keyframes scroll-animation {
      0% {
        transform: translateX(0%);
      }
      100% {
        transform: translateX(-100%);
      }
    }
</style>
