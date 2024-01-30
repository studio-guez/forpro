<script lang="ts">
    import type {IHtmlContent} from "$lib/interfaces/cmsApiResponse";

    export let content: IHtmlContent;
</script>

<div>
    {@html content.content.text}
</div>
