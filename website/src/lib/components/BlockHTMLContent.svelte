<script lang="ts">
    import type {IHtmlContent} from "$lib/interfaces/cmsApiResponse";

    export let content: IHtmlContent;
</script>

<div class="s-block-html app-typo_text-content app-typo_text-content--typo-large" >
    {@html content.content.text}
</div>

<style lang="scss">
    .s-block-html {
        width: max(50rem, 100%);
    }
</style>
