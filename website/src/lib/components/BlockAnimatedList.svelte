<script>
import {LottiePlayer} from "@lottiefiles/svelte-lottie-player";
import {browser} from "$app/environment";
</script>

<div class="s-animated-list">
    {#if browser}
        <LottiePlayer
                src="/lottie/desktop-model-jeune_lottie.json"
                autoplay="{true}"
        />
    {/if}
</div>

<style lang="scss">
    .s-animated-list {
      height: 80vh !important;


        :global(.lottie-player svg) {
          height: 80vh !important;
        }
    }

</style>
