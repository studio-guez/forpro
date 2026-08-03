<script lang="ts">
    import type {IBlockImage, IImage} from "$lib/interfaces/cmsApiResponse.js";

    export let content: IBlockImage
    export let image: IImage[]
</script>


<div class="s-block-image"
     class:is-fixed={content.content.fixed === 'true'}
     style="background-image: url({image[0]?.resize.large}); background-position: {image[0]?.focus};"
>
    {#if (content.content.fixed === 'false')}
        <img
                class="s-block-image__img"
                src="{image[0]?.resize.large}"
                alt="illustration"
        />
    {/if}
</div>


