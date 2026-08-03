<script lang="ts">
    import type {IDropdown} from "$lib/interfaces/cmsApiResponse";

    export let content: IDropdown;

    let currentIndex = -1

    function changeOpenState(index: number) {
        currentIndex = index === currentIndex ? -1 : index
    }

    function handleKeyDown_changeOpenState(event:  KeyboardEvent, index: number) {
        if(event.code.toLowerCase() === 'enter') changeOpenState(index)
    }
</script>

<div class="s-dropdown"
>
    <div class="s-dropdown__item-container">

        {#each content.content.dropdown as dropdown, index}

            <div class="s-dropdown__item"
                 class:is-active={currentIndex === index}
            >
                <div class="s-dropdown__item__line s-dropdown__item__line--top" ></div>
                <div class="s-dropdown__item__line s-dropdown__item__line--right" ></div>
                <div class="s-dropdown__item__line s-dropdown__item__line--left" ></div>

                <div class="s-dropdown__item__header app-flex app-flex--justify_space-between"
                     on:click={() => changeOpenState(index)}
                     on:keydown={(event) => handleKeyDown_changeOpenState(event, index)}
                     role="button"
                     tabindex="0"
                >
                    <div>{dropdown.title}</div>
                    <div class="s-dropdown__item__header__icon">
                        {#if (currentIndex === index) }
                            <img alt="fermer la description" src="/remove_FILL0_wght400_GRAD0_opsz24.svg">
                        {:else}
                            <img alt="ouvrir la description" src="/add_FILL0_wght400_GRAD0_opsz24.svg">
                        {/if}
                    </div>
                </div>
                {#if (currentIndex === index)}
                    <div class="s-dropdown__item__content app-remove-margin-child"
                    >{@html dropdown.content}</div>
                    {#if (dropdown.link)}
                        <a class="s-dropdown__item__button app-button app-button--rounded"
                           style="--app-button--color: white;--app-button--background-color: blue;--app-button--border-color: blue;"
                           href="{dropdown.link}"
                        >pour aller plus loin</a>
                    {/if}
                {/if}
            </div>

        {/each}



    </div>
</div>

