<div class="s-page"
>
    <AppPage
            data="{data}"
    />
</div>


<script lang="ts">
    import {type IPage} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";

    export let data: IPage;

</script>

<style lang="scss">

</style>
