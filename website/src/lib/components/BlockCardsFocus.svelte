<script lang="ts">
    import type {ICardsFocus} from "$lib/interfaces/cmsApiResponse";
    import {onMount} from "svelte";
    import {browser} from "$app/environment";
    import {LottiePlayer} from "@lottiefiles/svelte-lottie-player";

    export let content: ICardsFocus;
    let cardsFocusElement: HTMLElement

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

