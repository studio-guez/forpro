<script lang="ts">
    import type {IList} from "$lib/interfaces/cmsApiResponse";

    export let content: IList;
</script>

<div>
    <h2>list</h2>
    {content.type}
</div>
