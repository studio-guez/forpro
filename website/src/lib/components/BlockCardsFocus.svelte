<script lang="ts">
    import type {ICardFocusItem, ICardsFocus} from "$lib/interfaces/cmsApiResponse";
    import {onMount} from "svelte";

    export let content: ICardsFocus;
    let cardsFocusElement: HTMLElement

    const mapImage: { entreprises: string; jeunes: string; entourage: string } = {
        'entreprises': 'https://images.unsplash.com/photo-1494883759339-0b042055a4ee?q=80&w=2800&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        'entourage': 'https://images.unsplash.com/photo-1472220625704-91e1462799b2?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        'jeunes': 'https://images.unsplash.com/photo-1507537509458-b8312d35a233?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
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
    }

    .s-card-focus__card__text-box {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1;
      width: 100%;
    }

    .s-card-focus__card__title {
      color: var(--app-color--green);
      font-size: 4rem;
      line-height: 4rem;
      text-align: center;
      width: 100%;
      max-width: 10em;
    }

    .s-card-focus__card__subtitle {
      color: var(--app-color--blue);
      font-size: 2rem;
      line-height: 2rem;
      text-align: center;
      width: 100%;
      max-width: 10em;
    }

    .s-card_focus__img {
      position: relative;
      display: block;
      mask: url('/svg/Forme1-05.svg'), url('/svg/Forme1-05.svg');
      mask-repeat: no-repeat;
      mask-origin: border-box;
      mask-position: -210%, 500%;
      animation-fill-mode: forwards !important;
      .is-visible & {
        animation: mask-animation 2s;
      }
    }

    @keyframes mask-animation {
      0% {
        mask-position: -210%, 500%;
      }
      100% {
        mask-position: 30%, 90%;
      }
    }
</style>
