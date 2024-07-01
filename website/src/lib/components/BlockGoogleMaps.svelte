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
        width: min(70rem, 100%);
        margin: auto;
        aspect-ratio: 2/1;
    }
</style>
