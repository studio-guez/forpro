<script lang="ts">
    import type {ICta, IImage} from "$lib/interfaces/cmsApiResponse";
    import {onMount, tick} from "svelte";

    export let content: ICta;
    export let image: IImage[];

    let textAnimatedWrapper: HTMLDivElement | undefined

    onMount(async ()=> {
        await tick()

        if( textAnimatedWrapper === undefined ) return

        const textWrapperWidth = textAnimatedWrapper.getBoundingClientRect().width

        const speed = 150;
        const duration = textWrapperWidth / speed;

        textAnimatedWrapper.style.animationDuration = `${duration}s`

    })

</script>

<div class="s-block-cta app-flex app-flex--justify_center"
     id="{content.id}"
>
    <a class="s-block-cta__button {content.content.styles} app-button app-button--rounded app-button--xl"
       class:has-icon={image.length > 0}
       class:app-button--without-over-effect={image.length > 0 || content.content.styles === 'style1'}
       href="{content.content.link}"
       style="
            --s-cat-background-color: {content.content.backgroundcolor};
            --s-cat-color: {content.content.textcolor};
        "
       target="{content.content.target_blank === 'true' ? '_blank' : ''}"
    >
        {#if (image.length > 0)}
            <img class="s-block-cta__icon"
                 src={image[0].url}
                 alt="icon illustratif pour le bouton"/>
        {/if}
        {#if (content.content.styles === 'style1')}
            <div class="s-block-cta__text-animated" bind:this={textAnimatedWrapper}>
                <div class="s-block-cta__text-animated__text">{content.content.text}</div>
                <div class="s-block-cta__text-animated__duplication">{content.content.text}</div>
            </div>
        {:else}
            <div>
                {content.content.text}
            </div>
        {/if}
    </a>
</div>


