<script lang="ts">
    import type {ICards} from "$lib/interfaces/cmsApiResponse";

    export let content: ICards;
</script>

<div>
    <h2>cards</h2>
    {content.type}
</div>
