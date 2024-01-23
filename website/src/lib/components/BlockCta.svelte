<script lang="ts">
    import type {ICta} from "$lib/interfaces/cmsApiResponse";

    export let content: ICta;
</script>

<div>
    <h2>CTA</h2>
    {content.type}
</div>
