<script lang="ts">
import {LottiePlayer} from "@lottiefiles/svelte-lottie-player";
import {browser} from "$app/environment";
import type {AnimatedListStyle, IAnimatedList} from "$lib/interfaces/cmsApiResponse.js";


export let content: IAnimatedList

const lottiePath: {[key: AnimatedListStyle]: {desktop: string, mobil: string}} = {
    'entreprises' :     {desktop: '/lottie/desktop-model-entreprise_lottie.json',   mobil: '/lottie/mobile-model-entreprise_lottie.json'},
    'entourage' :       {desktop: '/lottie/desktop-model-entourage_lottie.json',    mobil: '/lottie/mobile-model-entourage_lottie.json'},
    'jeunes' :          {desktop: '/lottie/desktop-model-jeune_lottie.json',        mobil: '/lottie/mobile-model-jeune_lottie.json'},
    'explore' :         {desktop: '/lottie/desktop-model-explore_lottie.json',      mobil: '/lottie/mobile-model-explore_lottie.json'},
    'leLab' :           {desktop: '/lottie/desktop-model-lab_lottie.json',          mobil: '/lottie/mobile-model-lab_lottie.json'},
    'campus' :          {desktop: '/lottie/desktop-model-campus_lottie.json',       mobil: '/lottie/mobil-model-campus_lottie.json'},
}

function scrollToBottom(e: Event) {
    if( ! (e.target instanceof HTMLElement) ) return

    const scrollContainer = document.querySelector('.s-layout')
    if( ! (scrollContainer instanceof HTMLElement) ) return

    scrollContainer.scrollTo({
        top: scrollContainer.scrollTop + e.target.getBoundingClientRect().bottom,
        behavior: 'smooth',
    })
}


</script>

<div class="s-animated-list"
>
    <div on:click={scrollToBottom}
         on:keyup={(e) => e.keyCode === 32 || e.keyCode === 13 && scrollToBottom(e) }
         role="button"
         tabindex="0"
         class="s-animated-list__desktop__click-event"
    ></div>
    {#if browser}
        <div class="s-animated-list__desktop"
        >
            <LottiePlayer
                    src="{lottiePath[content.content.style].desktop}"
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
                    loop="{['explore', 'leLab'].includes(content.content.style)}"
            />
        </div>
        <div class="s-animated-list__mobile">
            <LottiePlayer
                    src="{lottiePath[content.content.style].mobil}"
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
                    loop="{['explore', 'leLab'].includes(content.content.style)}"
            />
        </div>
    {/if}
</div>

