<script lang="ts">
    import type {IList} from "$lib/interfaces/cmsApiResponse";

    export let content: IList;
</script>

<div class="s-app__list app-grid--column-2 app-grid--column-gap_regular app-grid--row-gap_half" >
    {#each content.content.list as item}
        <div>
            {item.key}
        </div>
        <div>
            {item.value}
        </div>
    {/each}
</div>

<style lang="scss" >
.s-app__list {

}
</style>

