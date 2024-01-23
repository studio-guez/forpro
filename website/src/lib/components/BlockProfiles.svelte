<script lang="ts">
    import type {IProfiles} from "$lib/interfaces/cmsApiResponse";

    export let content: IProfiles;
</script>

<div>
    <h2>profile</h2>
    {content.type}
</div>
