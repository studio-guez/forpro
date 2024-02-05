<script lang="ts">
import {siteInfo} from "../../store";
</script>

<footer class="s-footer app-grid--column-2 app-width-gutter app-grid--gap_regular"
>
    <div class="app-typo_text-content">
        {@html $siteInfo.footer}
    </div>
    <div>
        <p>
            right content
        </p>
    </div>
    <div class="app-flex__basis-1-1">
        <div style="font-size: 20vw; line-height: 25vw; text-align: center">FORPRO</div>
    </div>
</footer>

<style lang="scss">
    .s-footer {
      width: 100%;
      box-sizing: border-box;
      background: #b2b2a1;
    }
</style>
