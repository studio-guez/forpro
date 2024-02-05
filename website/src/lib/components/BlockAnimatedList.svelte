<script>
import {LottiePlayer} from "@lottiefiles/svelte-lottie-player";
</script>

<div class="s-animated-list">
    <LottiePlayer
            src="/lottie/desktop-model-jeune_lottie.json"
            autoplay="{true}"
    />
</div>

<style lang="scss">
    .s-animated-list {
      height: 80vh !important;


        :global(.lottie-player svg) {
          height: 80vh !important;
        }
    }

</style>
