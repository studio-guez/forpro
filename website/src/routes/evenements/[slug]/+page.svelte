<div class="s-page-events-slug s-page-events-slug--events__slug"
>
    <div class="s-page-events-slug__hero">
        <h1 class="s-page-events-slug__hero__title"
        >{data.pageInfo.title}</h1>
        <div class="s-page-events-slug__hero__date">{@html formatDate(data.pageInfo.datestart)}</div>
    </div>
    <AppPage
            data="{data}"
    />
</div>


<script lang="ts">
    import {type IPage_Event} from "$lib/interfaces/cmsApiResponse";
    import AppPage from "$lib/components/AppPage.svelte";
    import {formatDate} from "$lib/utils/formatDate";

    export let data: IPage_Event;

</script>

<style lang="scss">
    @use "../../../style/_scss-params";

    .s-page-events-slug {
        margin-bottom: 5rem;
    }

    .s-page-events-slug__hero {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .s-page-events-slug__hero__date {
        background: var(--app-color--pink);
        color: white;
        border-radius: 1rem;
        white-space: nowrap;
        padding: .15em 1em .35em;
    }

    .s-page-events-slug__hero__title {
        font-weight: 600;
        color: var(--app-color--blue);
    }

    .s-page-events-slug__hero {
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
