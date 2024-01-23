<script lang="ts">
    import type {IHtmlContent} from "$lib/interfaces/cmsApiResponse";

    export let content: IHtmlContent;
</script>

<div>
    <h2>Body (BlockHTMLContent)</h2>
    {content.type}
</div>
