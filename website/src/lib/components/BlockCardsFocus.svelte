<script lang="ts">
    import type {ICardFocusItem, ICardsFocus} from "$lib/interfaces/cmsApiResponse";
    import {onMount} from "svelte";
    import {browser} from "$app/environment";
    import {LottiePlayer} from "@lottiefiles/svelte-lottie-player";

    export let content: ICardsFocus;
    let cardsFocusElement: HTMLElement

    const mapImage: { entreprises: string; jeunes: string; entourage: string } = {
        'entreprises': '/Forpro©photo-RaphaelleMueller-Entreprises-1.jpeg',
        'entourage': '/Forpro©photo-RaphaelleMueller-Entourage-1.jpg',
        'jeunes': '/Forpro©photo-RaphaelleMueller-Jeunes-1.jpg',
    }

    function getImageUrl(card: ICardFocusItem) {
        return {
            img:    mapImage[card.style],
        }

    }

    onMount(() => {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible')
                } else {
                    entry.target.classList.remove('is-visible')
                }
            });
        });

        cardsFocusElement.querySelectorAll('.s-card-focus__card').forEach((element) => {
            observer.observe(element)
        })
    });

</script>

<div class="s-card-focus"
     bind:this={cardsFocusElement}
>
    {#each content.content.cards as card}
        <div
                class="s-card-focus__card {card.style}"
        >
            <div class="s-card-focus__card__text-box app-flex app-flex--column app-flex--align_center app-flex--justify_center">
                <div class="s-card-focus__card__title" >{card.title}</div>
                <div class="s-card-focus__card__subtitle" >{card.subtitle}</div>
                {#if (card.link)}
                    {#if (card.style === 'entreprises')}
                        <a class="s-card-focus__card__button app-button app-button--rounded"
                           href="{card.link}">Pour les entreprises</a>
                    {/if}
                    {#if (card.style === 'entourage')}
                        <a class="s-card-focus__card__button app-button app-button--rounded"
                           href="{card.link}">Pour les parents et l'entourage</a>
                    {/if}
                    {#if (card.style === 'jeunes')}
                        <a class="s-card-focus__card__button app-button app-button--rounded"
                           href="{card.link}">Pour les jeunes</a>
                    {/if}
                {/if}
            </div>
            {#if browser}
                {#if (card.style === 'entreprises')}
                    <div class="s-card-focus__card__animation s-card-focus__card__animation--desktop">
                        <LottiePlayer
                                src="lottie/desktop-entreprise.json"
                                autoplay="{true}"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%"
                                direction="1"
                                mode="normal"
                                width="100%"
                                height="100%"
                                controls="{false}"
                                controlsLayout="[]"
                                renderer="svg"
                        />
                    </div>
                    <div class="s-card-focus__card__animation s-card-focus__card__animation--mobile">
                        <LottiePlayer
                                src="lottie/mobile-entreprise.json"
                                autoplay="{true}"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%"
                                direction="1"
                                mode="normal"
                                width="100%"
                                height="100%"
                                controls="{false}"
                                controlsLayout="[]"
                                renderer="svg"
                        />
                    </div>
                {/if}
                {#if (card.style === 'entourage')}
                    <div class="s-card-focus__card__animation s-card-focus__card__animation--desktop">
                        <LottiePlayer
                                src="lottie/desktop-entourage.json"
                                autoplay="{true}"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%"
                                direction="1"
                                mode="normal"
                                width="100%"
                                height="100%"
                                controls="{false}"
                                controlsLayout="[]"
                                renderer="svg"
                        />
                    </div>
                    <div class="s-card-focus__card__animation s-card-focus__card__animation--mobile">
                        <LottiePlayer
                                src="lottie/mobile-entourage.json"
                                autoplay="{true}"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%"
                                direction="1"
                                mode="normal"
                                width="100%"
                                height="100%"
                                controls="{false}"
                                controlsLayout="[]"
                                renderer="svg"
                        />
                    </div>
                {/if}
                {#if (card.style === 'jeunes')}
                    <div class="s-card-focus__card__animation s-card-focus__card__animation--desktop"
                    >
                        <LottiePlayer
                                src="lottie/desktop-jeunes.json"
                                autoplay="{true}"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%"
                                direction="1"
                                mode="normal"
                                width="100%"
                                height="100%"
                                controls="{false}"
                                controlsLayout="[]"
                                renderer="svg"
                        />
                    </div>
                    <div class="s-card-focus__card__animation s-card-focus__card__animation--mobile"
                    >
                        <LottiePlayer
                                src="lottie/mobile-jeunes.json"
                                autoplay="{true}"
                                background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%"
                                direction="1"
                                mode="normal"
                                width="100%"
                                height="100%"
                                controls="{false}"
                                controlsLayout="[]"
                                renderer="svg"
                        />
                    </div>
                {/if}
            {/if}

        </div>
    {/each}
</div>

<style lang="scss" >
  @use "../../style/_scss-params";

    .s-card-focus__card {
      background: var(--app-color--blue);
      position: relative;
      overflow: hidden;
      user-select: none;
      color: white;

      &.entreprises {
        background: var(--app-color--green);
      }
      &.jeunes {
        background: var(--app-color--pink);
      }
      &.entourage {
        background: var(--app-color--orange);
      }
    }

    .s-card-focus__card__text-box {
      position: absolute;
      top: 50%;
      left: 50%;
      z-index: 1;
      width: 100%;
      transform: translate(-50%, -50%) scale(1.1);

      .is-visible & {
        transition: 3s cubic-bezier(0.5,0,0,1);
        transform: translate(-50%, -50%) scale(1);
      }
    }

    .s-card-focus__card__title {
      font-size: clamp(1rem, 7vw, 4rem);
      line-height: 1em;
      text-align: center;
      width: 100%;
      max-width: 12em;
      font-weight: 600;
    }

    .s-card-focus__card__subtitle {
      font-weight: 500;
      font-size: 2rem;
      line-height: 2rem;
      text-align: center;
      width: 100%;
      max-width: 15em;
      margin-top: 1rem;
    }

    .s-card-focus__card__button {
      margin-top: 1rem;
    }

    .s-card_focus__img {
      position: relative;
      display: block;
      height: 90vh;
      min-height: 50vw;
      width: 100%;
      object-fit: cover;
    }

    .s-card_focus__color-filter {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      transition: background-color 2.5s cubic-bezier(0.5,0,0,1);
    }

    .s-card-focus__card__animation--desktop {
      display: block;

      @media (max-width: scss-params.$fp-breakpoint-xs) {
        display: none;
      }
    }
    .s-card-focus__card__animation--mobile {
      display: none;

      @media (max-width: scss-params.$fp-breakpoint-xs) {
        display: block;
      }
    }

</style>
