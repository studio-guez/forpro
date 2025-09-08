<script lang="ts">
    import type {ICta, IImage} from "$lib/interfaces/cmsApiResponse";
    import {onMount, tick} from "svelte";

    export let content: ICta;
    export let image: IImage[];

    let textAnimatedWrapper: HTMLDivElement | undefined

    onMount(async ()=> {
        await tick()

        if( textAnimatedWrapper === undefined ) return

        const textWrapperWidth = textAnimatedWrapper.getBoundingClientRect().width

        const speed = 150;
        const duration = textWrapperWidth / speed;

        textAnimatedWrapper.style.animationDuration = `${duration}s`

    })

</script>

<div class="s-block-cta app-flex app-flex--justify_center"
     id="{content.id}"
>
    <a class="s-block-cta__button {content.content.styles} app-button app-button--rounded app-button--xl"
       class:has-icon={image.length > 0}
       class:app-button--without-over-effect={image.length > 0 || content.content.styles === 'style1'}
       href="{content.content.link}"
       style="
            --s-cat-background-color: {content.content.backgroundcolor};
            --s-cat-color: {content.content.textcolor};
        "
       target="{content.content.target_blank === 'true' ? '_blank' : ''}"
    >
        {#if (image.length > 0)}
            <img class="s-block-cta__icon"
                 src={image[0].url}
                 alt="icon illustratif pour le bouton"/>
        {/if}
        {#if (content.content.styles === 'style1')}
            <div class="s-block-cta__text-animated" bind:this={textAnimatedWrapper}>
                <div class="s-block-cta__text-animated__text">{content.content.text}</div>
                <div class="s-block-cta__text-animated__duplication">{content.content.text}</div>
            </div>
        {:else}
            <div>
                {content.content.text}
            </div>
        {/if}
    </a>
</div>


<style lang="scss">
  @use "../../style/_scss-params";

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
      border: none;

      @media (max-width: scss-params.$fp-breakpoint-xs) {
        font-size: .75rem;
      }

      &.style1 {
        width: 100%;
        height: 2rem;
        border-radius: 0;
        font-weight: 400;
        padding: 0;
        font-size: min(max(1rem, 2vw), 1.2rem);

        .s-block-cta__text-animated {
          top: 0;
          left: 0;
          padding-top: .5rem;
          padding-bottom: .5rem;
          position: absolute;
          display: flex;
          width: auto;
          overflow: unset;
          //todo: dynamic timing by string length
          animation: scroll-animation 25s linear infinite;

          .s-block-cta__text-animated__text,
          .s-block-cta__text-animated__duplication {
            padding-left: 15em;
            white-space: nowrap;
            width: auto;
          }
        }
      }

      &.style2 {
        font-weight: 600;
        white-space: normal;
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
        transform: translateX(-50%);
      }
    }
</style>
