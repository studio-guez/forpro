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
}


</script>

<div class="s-animated-list">
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
                    loop="{true}"
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
                    loop="{true}"
            />
        </div>
    {/if}
</div>

<style lang="scss">
  $s-animated-list__breakpoint: 800px;

  .s-animated-list {
    min-height: 80vh;
    container-type: inline-size;
  }
    .s-animated-list__desktop {
        :global(.lottie-player svg) {
          height: max(80vh, 500px) !important;
        }

      @container (width < #{$s-animated-list__breakpoint}) {
        display: none;
      }
    }

    .s-animated-list__mobile {
      :global(.lottie-player svg) {
        min-height: 90vh !important;
        width: 100% !important;
      }
      @container (width > #{$s-animated-list__breakpoint}) {
        display: none;
      }
    }

</style>
