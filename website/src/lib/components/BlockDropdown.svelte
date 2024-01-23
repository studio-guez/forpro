<script lang="ts">
    import type {IDropdown} from "$lib/interfaces/cmsApiResponse";

    export let content: IDropdown;
</script>

<div>
    <h2>dropdown</h2>
    {content.type}
</div>
