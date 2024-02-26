<a
        href="{content.content.link}"
        target="_blank"
>
    <img
            alt="google maps"
            src="/FP_plan_2--web.png"
    />
</a>

<script lang="ts">
    import type {IGoogleMaps} from "$lib/interfaces/cmsApiResponse.js";

    export let content: IGoogleMaps
</script>

<style>
    img {
        background: var(--app-color--blue);
        display: block;
        width: max(50rem, 100%);
        aspect-ratio: 2/1;
    }
</style>
