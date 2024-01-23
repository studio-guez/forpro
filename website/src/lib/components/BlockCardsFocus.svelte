<script lang="ts">
    import type {ICardsFocus} from "$lib/interfaces/cmsApiResponse";

    export let content: ICardsFocus;
</script>

<div>
    <h2>cardsFocus</h2>
    {content.type}
</div>
