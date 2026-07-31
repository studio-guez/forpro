<script lang="ts">
    import type {Slot} from "$lib/interfaces/variables";

    let currentPage: number = 0;
    const itemsPerPage: number = 5;

    export let slots: Slot[] | {status: 'error'} = [];
    export let selectedSlotId: Slot | null = null;
    export let labelNoSlots: string = '';
    export const loading: boolean = false;

    const maxPage: number = Array.isArray(slots) ? Math.ceil(slots.length / itemsPerPage) - 1 : 0;

    const nextPage = () => {
        currentPage = (currentPage === maxPage) ? 0 : currentPage + 1;
    }
    const previousPage = () => {
        currentPage = (currentPage > 0) ? currentPage - 1 : maxPage;
    }
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    };
</script>

<div class="overflow-y-auto h-full flex flex-col pt-1 pr-2 mt-2 space-y-2 mb-5">
    {#if Array.isArray(slots) && slots.length > 0}
        {#each slots.slice(currentPage * itemsPerPage, (currentPage + 1) * itemsPerPage) as slot, i (slot)}
            <div class="relative mt-0 w-full text-base rounded border border-solid"
                 class:border-black={selectedSlotId === slot}
                 role="button"
                 tabindex="0"
                 on:click={() => selectedSlotId = slot}
                 on:keydown={(e) => (e.key === 'Enter' || e.key === ' ') && (selectedSlotId = slot)}
            >
                <div class="py-3 w-full font-light text-center cursor-pointer">
                    {formatDate(slot.date)}
                </div>
            </div>
        {/each}
        {#if currentPage > 0 || slots.length > itemsPerPage}
            <div class="w-full border-solid border-gray-200 align-center divide-x-2 border-2 flex justify-center mt-2 space-x-2">
                {#if currentPage > 0}
                    <div class="w-full cursor-pointer" role="button" tabindex="0" aria-label="Page précédente" on:click={previousPage} on:keydown={(e) => (e.key === 'Enter' || e.key === ' ') && previousPage()}>
                        <svg class="mx-auto h-6 w-6 transform transition-transform duration-200 rotate-180"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                {/if}
                {#if slots.length - (currentPage * itemsPerPage + itemsPerPage) > 0}
                    <div class="w-full cursor-pointer" role="button" tabindex="0" aria-label="Page suivante" on:click={nextPage} on:keydown={(e) => (e.key === 'Enter' || e.key === ' ') && nextPage()}>
                        <svg class="mx-auto h-6 w-6 transform transition-transform duration-200"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                {/if}
            </div>
        {/if}
    {:else}
        <div class="relative mt-0 w-full text-base rounded border border-solid">
            <div class="py-3 w-full font-light text-center cursor-pointer justify-center text-red-600">
                {labelNoSlots}
            </div>
        </div>
    {/if}
</div>

<style>
    @keyframes expand {
        from {
            max-height: 0;
        }
        to {
            max-height: 100%;
        }
    }

    @keyframes contract {
        from {
            max-height: 100%;
        }
        to {
            max-height: 0;
        }
    }
</style>
