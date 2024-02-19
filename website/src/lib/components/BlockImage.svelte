<script lang="ts">
    import type {IBlockImage, IImage} from "$lib/interfaces/cmsApiResponse.js";

    export let content: IBlockImage
    export let image: IImage[]
</script>


<div>
    <img
            class:is-fixed={content.content.fixed}
            src="{image[0]?.resize.large}"
            alt="illustration"
    />
</div>


