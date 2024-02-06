<script lang="ts">
    import type {ICardFocusItem, ICardsFocus} from "$lib/interfaces/cmsApiResponse";
    import {onMount} from "svelte";

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
            </div>
            <img class="s-card_focus__img" alt="juste un masque" src="{getImageUrl(card).img}" />
        </div>
    {/each}
</div>

<style lang="scss" >
    .s-card-focus__card {
      background: var(--app-color--blue);
      position: relative;
      overflow: hidden;

      &.entreprises {
        background: var(--app-color--blue);
      }
      &.jeunes {
        background: var(--app-color--orange);
      }
      &.entourage {
        background: var(--app-color--pink);
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
      color: var(--app-color--green);
      font-size: 4rem;
      line-height: 4rem;
      text-align: center;
      width: 100%;
      max-width: 12em;
      font-weight: 600;

      .jeunes & {
        color: var(--app-color--pink);
        text-align: left;
        position: relative;
        top: 20%;
        left: -10%;
      }
    }

    .s-card-focus__card__subtitle {
      color: white;
      font-weight: 500;
      font-size: 2rem;
      line-height: 2rem;
      text-align: center;
      width: 100%;
      max-width: 15em;
      margin-top: 1rem;
    }

    .s-card_focus__img {
      position: relative;
      display: block;
      mask-repeat: no-repeat;
      mask-origin: border-box;
      mask-position: -210%, 500%;
      animation-fill-mode: forwards !important;
      height: 90vh;
      min-height: 35rem;
      width: 100%;
      object-fit: cover;
      mask-size: auto 90%, auto 110%;
      mask-image: url('/svg/Pilule-45-gauche.svg'), url('/svg/Pilule-0.svg');

      .jeunes & {
        mask-image: url('/svg/Ovale-0.svg'), url('/svg/Ovale-45.svg');
      }

      .entreprises & {
        mask-image: url('/svg/Feuille-1.svg'), url('/svg/Feuille-2.svg');
        mask-size: auto 110%, auto 115%;
        object-position: 10% 80%;
      }

      .is-visible & {
        animation: mask-animation 2.5s cubic-bezier(0.5,0,0,1);
      }

      .is-visible.entreprises & {
        animation: mask-animation-2 2.5s cubic-bezier(0.5,0,0,1);
      }
    }

    @keyframes mask-animation {
      0% {
        mask-position: -170%, 150%;
        transform: scale(.85);
      }
      100% {
        mask-position: 30%, 90%;
        transform: scale(1);
      }
    }


    @keyframes mask-animation-2 {
      0% {
        mask-position: 0% -400%, 90% -500%;
        transform: scale(.85);
      }
      100% {
        mask-position: 10%, 90%;
        transform: scale(1);
      }
    }
</style>
