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
      padding: max(2rem, 4vw) max(4rem, 6vw);
      align-items: center;
      box-sizing: border-box;
      border-radius: max(1rem, 2vw);
      text-decoration: none;
      text-align: center;
      display: inline-block;
      line-height: 1em;
      font-size: max(2rem, 4vw);

      &.style2 {
        border-radius: 10em;
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

</style>
