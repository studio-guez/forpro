<div class="s-page"
>
    <div class="s-page__hero">
        <h1 class="s-page__hero__title"
        >{data.pageInfo.title}</h1>
    </div>
    <AppPage
            data="{data}"
    />
</div>


<script lang="ts">
    import {type IPage_Event} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";

    export let data: IPage_Event;

</script>

<style lang="scss">
    @use "../../../style/_scss-params";

    .s-page__hero__title {
        font-weight: 600;
    }

    .s-page__hero {
        overflow: hidden;
        box-sizing: border-box;
        width: 100%;
        padding: 4rem 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;

        &.first-child-is-animation {
            padding-top: 0;
            padding-bottom: 1rem;
            margin-bottom: -2rem;
        }

        @media (max-width: scss-params.$fp-breakpoint-xs) {
            padding-top: 1rem;
            padding-bottom: 3rem;
        }
    }
</style>
