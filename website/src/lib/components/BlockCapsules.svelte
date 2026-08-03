<script lang="ts">
    import type {ICapsules} from "$lib/interfaces/cmsApiResponse";

    export let content: ICapsules;
</script>

<div class="s-block-capsule"
>
    {#each content.content.capsules as capsule}
        <div class="s-block-capsule__item app-flex app-flex--column app-flex--align_center app-flex--gap_half">
            <div class="s-block-capsule__item__content">
                <h3 class="s-block-capsule__item__content__title"
                >{capsule.title}</h3>
                <div class="s-block-capsule__item__content__text app-remove-margin-child"
                >{@html capsule.text}</div>
            </div>

            {#if capsule.style === 'style1'}
                <img class="s-block-capsule__item__img" src="/svg/Forme1-03.svg"
                     alt="graphique pour illustrer un item"/>
            {:else if capsule.style === 'style2' }
                <img class="s-block-capsule__item__img" src="/svg/Forme1-11.svg"
                     alt="graphique pour illustrer un item"/>
            {:else if capsule.style === 'style3' }
                <img class="s-block-capsule__item__img" src="/svg/Forme1-12.svg"
                     alt="graphique pour illustrer un item"/>
            {:else if capsule.style === 'style4' }
                <img class="s-block-capsule__item__img" src="/svg/Forme1-14.svg"
                     alt="graphique pour illustrer un item"/>
            {/if}
        </div>
    {/each}
</div>



