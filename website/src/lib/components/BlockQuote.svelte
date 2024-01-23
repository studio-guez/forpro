<script lang="ts">
    import type {IQuote} from "$lib/interfaces/cmsApiResponse";

    export let content: IQuote;
</script>

<div>
    <h2>quote</h2>
    {content.type}
</div>
