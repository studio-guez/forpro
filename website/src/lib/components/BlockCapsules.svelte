<script lang="ts">
    import type {ICapsules} from "$lib/interfaces/cmsApiResponse";

    export let content: ICapsules;
</script>

<div>
    <h2>capsule</h2>
    {content.type}
</div>
