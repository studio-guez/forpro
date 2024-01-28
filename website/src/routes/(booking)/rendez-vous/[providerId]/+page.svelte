<script lang="ts">
    import SveltyPicker, {config} from "svelty-picker";
    import {fr} from 'svelty-picker/i18n';

    import {getAvailableSlots} from "$lib/utils/easyappointments/api";
    import BookingSlots from "$lib/components/BookingSlots.svelte";
    import type {Slot} from "$lib/interfaces/variables";

    import { enhance } from "$app/forms";

    export let data;
    export let form;

    let step = 1;
    form = { appointment: null };

    const providerId = data.providerId;

    let selectedServiceId: number = data.services[0].id;
    let selectedSlotId: number = null;
    let selectedDate: string = null;

    let formattedDate: string = null;

    let services = data.services;
    let slots: Slot[] = data.availabilities;
    let loading: boolean = false;

    // PICKER CONFIG
    const today = new Date();
    const endDate = new Date(today.getFullYear(), today.getMonth() + 1, today.getDate());
    config.i18n = fr;

    const handleDateSelection = async (event) => {
        selectedDate = event.detail;
        slots = await getAvailableSlots(selectedDate, selectedServiceId, providerId);
        formattedDate = new Date(event.detail).toLocaleDateString('fr-CH');
    }

    const getServiceName = (id: number) => {
        return services.find(service => service.id === id).name;
    }

    const getServiceDuration = (id: number) => {
        return services.find(service => service.id === id).duration;
    }

    function disableDatesIfNotAvailable(date) {
        return date.getDay() === 0 || date.getDay() === 6
    }
</script>

<div class="h-full mx-auto w-full lg:max-w-4xl text-gray-700 flex flex-col justify-between max-h-300">
    <div class="relative flex flex-col flex-grow h-full overflow-hidden rounded-b md:rounded">
        <div class="flex items-center w-full leading-6 text-black border-b-0 border-solid md:p-4 border-x-0">
            <div class="flex flex-col justify-between mx-auto w-full h-full text-gray-700 bg-white lg:max-w-4xl md:rounded">
                <div class="flex shadow-sm overflow-hidden relative flex-col flex-grow h-full rounded-b md:rounded">
                    <div class="flex flex-wrap border-2 border-black border-solid h-full">
                        {#if step === 1}
                            <!-- left side -->
                            <div class="overflow-y-auto overflow-x-hidden py-2 px-4 w-full h-full text-center rounded-b md:w-1/2 md:rounded md:py-8">
                                <div class="flex flex-col justify-evenly min-h-full">

                                    <div>
                                        <div class="mt-4 sm:mt-2">
                                            <div class="inline-block overflow-hidden w-20 h-20 bg-gray-100 bg-cover rounded-full">
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

                                    <p class="flex w-full text-center justify-center mb-10 font-semibold">{data.content.bookingCalendarLabel}</p>

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
                            <div class="flex overflow-x-hidden justify-center flex-col border-l border-solid border-gray-200 p-6 w-full text-gray-600 md:w-1/2 md:p-10">
                                <div class="font-semibold">
                                    {data.content.bookingServicesLabel}
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
                                    {data.content.bookingSlotsLabel}
                                </div>

                                <BookingSlots
                                        labelNoSlots={data.content.bookingNoSlotsLabel}
                                        {loading}
                                        bind:selectedSlotId
                                        bind:slots/>

                                <button class="py-2 px-6 my-0 mr-0 ml-2 text-sm leading-5 text-center text-white normal-case bg-black bg-none rounded border-0 border-gray-500 border-solid cursor-pointer"
                                        type="button"
                                        class:bg-gray-400={selectedSlotId === null}
                                        class:cursor-not-allowed={selectedSlotId === null}
                                        disabled={selectedSlotId === null}
                                        on:click={() => step = 2}
                                >
                                    {data.content.bookingSlotConfirmationLabel}
                                </button>
                            </div>
                        {:else if step === 2}
                            <div class="overflow-auto px-6 pb-8 my-auto mx-auto h-full leading-6 text-gray-700">
                                {#if !form?.appointment}
                                    <div class="flex flex-col mx-auto max-w-lg h-full text-gray-700">
                                        <div class="px-1 mt-2 text-xl font-semibold leading-7 md:mt-16">
                                            {data.content.bookingAppointmentConfirmationLabel}
                                        </div>
                                        <div class="flex px-1 mt-2 text-base font-semibold text-indigo-600">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke-width="2"
                                                 stroke="currentColor"
                                                 aria-hidden="true"
                                                 class="block flex-shrink-0 w-6 h-6 align-middle"
                                            >
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                      class=""
                                                ></path>
                                            </svg>
                                            <span class="ml-2">
                                            {getServiceName(selectedServiceId)}, le {formattedDate} à {selectedSlotId}
                                        </span>
                                        </div>
                                        <div class="flex items-center px-1 mt-2 text-base font-semibold">
                                            <div class="flex">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke-width="2"
                                                     stroke="currentColor"
                                                     aria-hidden="true"
                                                     class="block flex-shrink-0 w-6 h-6 align-middle"
                                                >
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                          class=""
                                                    ></path>
                                                </svg>
                                                <span class="ml-2">{getServiceDuration(selectedServiceId)} min.</span>
                                            </div>
                                        </div>
                                        <form method="POST" class="flex flex-col flex-1 justify-between mt-6 w-full" use:enhance>
                                            <input hidden name="serviceId" value={selectedServiceId}/>
                                            <input hidden name="duration"
                                                   value="{getServiceDuration(selectedServiceId)}"/>
                                            <input hidden name="providerId" value={providerId}/>
                                            <input hidden name="slot" value={selectedSlotId}/>
                                            <input hidden name="date" value={selectedDate}/>
                                            <div class="overflow-x-hidden overflow-y-scroll px-1 pb-6 w-full h-full">
                                                <div class="flex flex-wrap -mx-2">
                                                    <div class="px-2 w-full sm:w-1/2">
                                                        <div class="flex flex-col">
                                                            <div class="block">
                                                                <label class="flex text-sm font-semibold leading-5 cursor-default"
                                                                       for="first">Prénom *</label>
                                                            </div>
                                                            <div class="relative mt-1 rounded-md"
                                                                 style="box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px; list-style: outside;"
                                                            >
                                                                <input name="firstname"
                                                                       type="text"
                                                                       placeholder="John"
                                                                       class="block py-2 px-3 m-0 w-full text-base bg-white rounded-md border border-gray-300 border-solid appearance-none cursor-text sm:text-sm sm:leading-5 focus:border-blue-600 focus:outline-offset-2"
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="px-2 mt-6 w-full sm:mt-0 sm:w-1/2">
                                                        <div class="flex flex-col">
                                                            <div class="block">
                                                                <label class="flex text-sm font-semibold leading-5 cursor-default">Nom *</label>
                                                            </div>
                                                            <div class="relative mt-1 rounded-md"
                                                                 style="box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px; list-style: outside;"
                                                            >
                                                                <input name="lastname"
                                                                       type="text"
                                                                       placeholder="Doe"
                                                                       class="block py-2 px-3 m-0 w-full text-base bg-white rounded-md border border-gray-300 border-solid appearance-none cursor-text sm:text-sm sm:leading-5 focus:border-blue-600 focus:outline-offset-2"
                                                                       value=""
                                                                />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-5">
                                                    <div class="block">
                                                        <label class="flex text-sm font-semibold leading-5 cursor-default"
                                                               for="email">
                                                            Email *
                                                        </label>
                                                    </div>
                                                    <div class="relative mt-1 rounded-md"
                                                         style="box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px; list-style: outside;"
                                                    >
                                                        <input name="email"
                                                               type="email"
                                                               placeholder="email@email.com"
                                                               class="block py-2 px-3 m-0 w-full text-base bg-white rounded-md border border-gray-300 border-solid appearance-none cursor-text sm:text-sm sm:leading-5 focus:border-blue-600 focus:outline-offset-2"
                                                               value="">
                                                    </div>
                                                    <div class="mt-5">
                                                        <div class="block">
                                                            <label class="flex text-sm font-semibold leading-5 cursor-default"
                                                                   for="email">Téléphone *</label>
                                                        </div>
                                                        <div class="relative mt-1 rounded-md"
                                                             style="box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px; list-style: outside;"
                                                        >
                                                            <input
                                                                    name="phone"
                                                                    type="tel"
                                                                    class="block py-2 px-3 m-0 w-full text-base bg-white rounded-md border border-gray-300 border-solid appearance-none cursor-text sm:text-sm sm:leading-5 focus:border-blue-600 focus:outline-offset-2"
                                                                    placeholder="+41(0)791232442"
                                                                    value="">
                                                        </div>
                                                    </div>
                                                    <div class="mt-5">
                                                        <div class="block">
                                                            <label class="flex text-sm font-semibold leading-5 cursor-default"
                                                                   for="email">Description</label>
                                                        </div>
                                                        <div class="relative mt-1 rounded-md"
                                                             style="box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px; list-style: outside;"
                                                        >
                                                        <textarea
                                                                name="description"
                                                                type="textarea"
                                                                placeholder="remarques..."
                                                                class="block py-2 px-3 m-0 w-full text-base bg-white rounded-md border border-gray-300 border-solid appearance-none cursor-text sm:text-sm sm:leading-5 focus:border-blue-600 focus:outline-offset-2"
                                                                rows="4"
                                                                value=""/>
                                                        </div>
                                                    </div>
                                                    <div class="flex bottom-0 left-0 flex-col flex-shrink-0 pb-6 mt-6 w-full text-right sm:mt-0 sm:pt-4">
                                                        <div class="flex justify-between w-full">
                                                            <button
                                                                    class="flex items-center py-2 px-6 m-0 text-sm leading-5 text-center normal-case bg-white bg-none rounded border border-gray-500 border-solid cursor-pointer"
                                                                    type="button"
                                                                    on:click={() => {step = 1}}
                                                            >
                                                                <svg
                                                                        stroke="currentColor"
                                                                        fill="none"
                                                                        stroke-width="2"
                                                                        viewBox="0 0 24 24"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        height="1em"
                                                                        width="1em"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        class="block align-middle"

                                                                >
                                                                    <polyline
                                                                            points="15 18 9 12 15 6"
                                                                            class=""

                                                                    ></polyline>
                                                                </svg
                                                                >
                                                                <span class="ml-2">Revenir en arrière</span>
                                                            </button>
                                                            <button class="py-2 px-6 my-0 mr-0 ml-2 text-sm leading-5 text-center text-white normal-case bg-black bg-none rounded border-0 border-gray-500 border-solid cursor-pointer">
                                                                {data.content.bookingAppointmentConfirmationLabel}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                {:else}
                                    <div class="mt-12 h-full w-full my-auto mx-auto">
                                        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 p-2 flex items-center justify-center mx-auto mb-3.5">
                                            <svg aria-hidden="true" class="w-8 h-8 text-green-500 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            <span class="sr-only">Success</span>
                                        </div>
                                        <p class="mb-4 text-center text-lg font-semibold text-black max-w-2xl">
                                            {data.content.bookingAppointmentSuccessLabel}
                                        </p>
                                    </div>
                                {/if}
                            </div>
                        {/if}
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
