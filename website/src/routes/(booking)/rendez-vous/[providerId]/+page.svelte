<script lang="ts">
    import SveltyPicker, {config} from "svelty-picker";
    import {fr} from 'svelty-picker/i18n';

    import {fetchAvailableSlots} from "$lib/utils/easyappointments/api";
    import BookingSlots from "$lib/components/BookingSlots.svelte";
    import type {Slot} from "$lib/interfaces/variables";

    export let data;

    const providerId = data.providerId;
    let selectedServiceId: number = data.services[0].id;
    let selectedSlotId: number = null;

    let services = data.services;
    let slots: Slot[] = data.availabilities;
    let loading: boolean = false;

    // PICKER CONFIG
    const today = new Date();
    const endDate = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
    config.i18n = fr;

    const handleDateSelection = async (event) => {
        const selectedDate = event.detail;
        slots = await fetchAvailableSlots(selectedDate, selectedServiceId, providerId);
        console.log(slots);
    }
</script>

<div class="h-full mx-auto w-full lg:max-w-4xl text-gray-700 flex flex-col justify-between max-h-300">
    <div class="relative flex flex-col flex-grow h-full overflow-hidden rounded-b md:rounded">
        <div class="flex items-center w-full leading-6 text-black border-b-0 border-solid md:p-4 border-x-0">
            <div class="flex flex-col justify-between mx-auto w-full h-full text-gray-700 bg-white lg:max-w-4xl md:rounded">
                <div class="flex shadow-sm overflow-hidden relative flex-col flex-grow h-full rounded-b md:rounded">
                    <div class="flex flex-wrap h-full">

                        <!-- left side -->
                        <div class="overflow-y-auto border-solid border-black border-2 overflow-x-hidden py-2 px-4 w-full h-full text-center rounded-b md:w-1/2 md:rounded md:py-8">
                            <div class="flex flex-col justify-evenly min-h-full">

                                <div>
                                    <div class="mt-4 sm:mt-2">
                                        <div class="inline-block overflow-hidden w-20 h-20 bg-gray-100 bg-cover rounded-full border-4 border-gray-100 border-solid">
                                            <svg>
                                                <path class="cls-2"
                                                      d="M269.68799,43.89295c-8.1104,0-15.4014-6.1738-15.4014-16.2158,0-10.4912,7.3662-16.2939,15.4014-16.2939s15.4746,5.8779,15.4746,16.2939c0,10.042-7.3672,16.2158-15.4746,16.2158M269.68799,.00035c-15.4766,.0743-27.6787,12.127-27.6787,27.6768,0,15.917,12.94721,27.6719,27.6787,27.5986,14.72951-.0742,27.6758-11.6816,27.6758-27.5986C297.36379,12.12735,285.16259-.07485,269.68799,.00035"
                                                      data-v-b110e4cd=""></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-2xl font-light leading-8">
                                        {data.content.headline}
                                    </div>
                                </div>

                                <div class="mt-2 mb-10 text-base font-light leading-5 break-words">
                                    <p class="m-0" style="list-style: outside;">
                                        {data.content.description}
                                    </p>
                                </div>

                                <p class="flex w-full text-center justify-center mb-10 font-semibold">1. Sélectionne une
                                    date</p>

                                <div class="flex justify-center">
                                    <SveltyPicker
                                            pickerOnly
                                            on:change={handleDateSelection}
                                            endDate={endDate}
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- right side -->
                        <div class="flex overflow-x-hidden justify-center flex-col border p-6 w-full text-gray-600 md:w-1/2 md:p-10">
                            <div class="font-semibold">
                                {data.content.servicesLabel}
                            </div>
                            <div class="flex flex-wrap flex-shrink-0 -mx-2">
                                {#each services as service}
                                    <div class="flex-grow p-2 mx-2 mt-3 text-xs leading-4 text-center text-black bg-gray-200 rounded border border-solid border-2 cursor-pointer"
                                         class:border-black={selectedServiceId === service.id}
                                         on:click={() => selectedServiceId = service.id}
                                    >
                                        {service.name}
                                    </div>
                                {/each}
                            </div>
                            <div class="mt-6 font-semibold" style="list-style: outside;">
                                {data.content.slotsLabel}
                            </div>

                            <BookingSlots
                                    labelNoSlots={data.content.bookingNoSlotsLabel}
                                    {loading}
                                    bind:selectedSlotId
                                    bind:slots/>

                            <button type="button"
                                    class="px-4 py-2.5 text-sm font-semibold rounded text-white shadow-sm ring-1 ring-inset ring-gray-300 disabled:bg-gray-300"
                                    disabled={selectedSlotId === null}
                                    class:cursor-not-allowed={selectedSlotId === null}
                                    class:bg-black={selectedSlotId !== null}
                            >
                                {data.content.bookingConfirmButtonLabel}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>

    :global(.std-component-wrap) {
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    :root {
        /* general */
        --sdt-bg-main: transparent; /** wrap background color */
        --sdt-shadow-color: white; /** wrap shadow color */
        --sdt-wrap-shadow: 0 1px 6px var(--sdt-shadow-color); /** wrap shadow settings */
        --sdt-radius: 0px; /** wrap radius */
        --sdt-color: #000; /** data to select(e.g date/time) text color (except header & buttons) */
        --sdt-color-selected: white; /** selected data(e.g date/time) text color */
        --sdt-header-color: #000; /** header items color (e.g. text & buttons) */
        --sdt-header-btn-bg-hover: #ccc; /** header items hover background color */
        --sdt-bg-selected: #000; /** selected data(e.g date/time) background color */

        /* action buttons */
        --sdt-today-bg: #000; /** date picker today button hover background color */
        --sdt-today-color: white; /** date picker today button text & border color */
        --sdt-clear-color: #dc3545; /** clear button text & border color */
        --sdt-clear-bg: transparent; /** clear button background color */
        --sdt-clear-hover-color: var(--sdt-bg-main); /** clear button hover text color */
        --sdt-clear-hover-bg: #dc3545; /** clear button hover background color */

        /* date picker */
        --sdt-table-selected-bg: var(--sdt-bg-selected); /** selected date background color */
        --sdt-table-disabled-date: #b22222; /** disabled dates text color */
        --sdt-table-disabled-date-bg: #eee; /** disabled dates background color */
        --sdt-table-bg: transparent; /** date picker inner table background color */
        --sdt-table-data-bg-hover: #eee; /** table selection data hover background color */
        --sdt-table-today-indicator: #ccc; /** date picker current day marker color */
    }
</style>
