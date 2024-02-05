<script lang="ts">
    import SveltyPicker, {config} from "svelty-picker";
    import {fr} from 'svelty-picker/i18n';

    import {getAvailableSlots} from "$lib/utils/booking/api";
    import BookingSlots from "$lib/components/BookingSlots.svelte";
    import type {Slot} from "$lib/interfaces/variables";

    import { enhance } from "$app/forms";
    import dayjs from "dayjs";

    export let data;
    export let form;

    let step = 1;
    form = { appointment: null };

    const providerId = data.providerId;

    let selectedServiceId: number = data.services ? data.services[0].id : null;
    let selectedSlotId: number = null;
    let selectedDate: string = dayjs().format('YYYY-MM-DD');

    let formattedDate: string = null;

    let services = data.services ?? [];
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
</script>

<div class="h-full mx-auto w-full lg:max-w-4xl text-gray-700 flex flex-col justify-between max-h-300">
    <div class="relative flex flex-col flex-grow h-full overflow-hidden rounded-b md:rounded">
        <div class="flex items-center w-full leading-6 text-black border-b-0 border-solid md:p-4 border-x-0">
            <div class="flex flex-col justify-between mx-auto w-full h-full text-gray-700 bg-white lg:max-w-4xl md:rounded">
                <div class="flex overflow-hidden relative flex-col flex-grow h-full rounded-b md:rounded">
                    <div class="flex flex-wrap h-full">
                        {#if step === 1}
                            <!-- left side -->
                            <div class="bg-[var(--app-color-beige)] overflow-y-auto overflow-x-hidden py-2 px-4 w-full h-full text-center rounded-b md:w-1/2 md:rounded md:py-8">
                                <div class="flex flex-col justify-evenly min-h-full">

                                    <div>
                                        <div class="mt-4 sm:mt-2">
                                            <div class="inline-block overflow-hidden w-20 h-20 bg-gray-100 bg-cover rounded-full">
                                                <svg id="Calque_2" data-name="Calque 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 297.36379 55.34905">
                                                    <defs>
                                                        <style>
                                                            .cls-1 {
                                                                fill: #3df069;
                                                            }

                                                            .cls-2 {
                                                                fill: #1754ff;
                                                            }
                                                        </style>
                                                    </defs>
                                                    <g id="Layer_1" data-name="Layer 1">
                                                        <g>
                                                            <g>
                                                                <path class="cls-2" d="M66.4321,49.34315c-11.3213,0-20.3144-9.1084-20.2343-21.7012,.081-12.7548,9.0722-21.7841,20.2343-21.7841,11.2408,0,20.314,9.0293,20.314,21.7841,0,12.5928-8.9121,21.7012-20.314,21.7012"/>
                                                                <path class="cls-1" d="M269.65089,48.02095c-10.4903,0-18.8242-8.5547-18.75-20.3819,.0762-11.9785,8.4082-20.4599,18.75-20.4599,10.416,0,18.8222,8.4814,18.8222,20.4599,0,11.8272-8.2578,20.3819-18.8222,20.3819"/>
                                                                <path class="cls-1" d="M66.4346,48.05895c-10.4898,0-18.8228-8.5546-18.7486-20.3818,.0747-11.9785,8.4068-20.46,18.7486-20.46,10.416,0,18.8232,8.4815,18.8232,20.46,0,11.8272-8.2578,20.3818-18.8232,20.3818M66.4346,.00035C51.0337,.00035,39.4277,12.12735,39.4277,27.75135c0,15.7686,11.5323,27.5977,27.0069,27.5977,15.5503,0,27.082-11.8291,27.082-27.5977C93.5166,12.12735,81.8359,.00035,66.4346,.00035"/>
                                                            </g>
                                                            <polygon points="0 1.04145 0 54.15855 7.6631 54.15855 7.6631 31.17325 32.438 31.17325 32.438 23.88125 7.6631 23.88125 7.6631 8.40765 34.4468 8.40765 34.4468 1.04145 0 1.04145"/>
                                                            <g>
                                                                <path d="M214.93209,24.47795h-7.3672V12.72205h7.0694c4.9111,0,7.8115,2.3076,7.8115,5.8037,0,3.4971-2.6035,5.9522-7.5137,5.9522m19.2696-6.5479c0-10.6386-9.4483-16.8886-19.7168-16.8886h-18.5987V54.15855h11.6787v-19.1181h5.5079l14.3574,19.1181h13.3926l-16.1436-21.4218c5.5049-2.6045,9.5225-7.291,9.5225-14.8067"/>
                                                                <path d="M109.6572,25.37055V8.33345h9.0757c6.10161,0,10.7149,2.6777,10.7149,8.4072,0,6.1748-4.6875,8.6299-10.7149,8.6299h-9.0757Zm27.5288-8.6299c0-10.7881-8.9287-15.6992-18.6757-15.6992h-16.4419V54.15855h7.5888v-21.498h6.7701l16.666,21.498h9.5224l-17.6328-22.538c5.7295-1.1153,12.2031-6.4727,12.2031-14.8799"/>
                                                                <path class="cls-2" d="M269.68799,43.89295c-8.1104,0-15.4014-6.1738-15.4014-16.2158,0-10.4912,7.3662-16.2939,15.4014-16.2939s15.4746,5.8779,15.4746,16.2939c0,10.042-7.3672,16.2158-15.4746,16.2158M269.68799,.00035c-15.4766,.0743-27.6787,12.127-27.6787,27.6768,0,15.917,12.94721,27.6719,27.6787,27.5986,14.72951-.0742,27.6758-11.6816,27.6758-27.5986C297.36379,12.12735,285.16259-.07485,269.68799,.00035"/>
                                                                <path d="M168.8071,25.81775h-7.0664V12.57365h7.0664c5.4336,0,8.7793,2.456,8.7793,6.5478,0,3.794-3.3457,6.6963-8.7793,6.6963m-.3711-24.7763h-18.3759V54.15855h11.6806v-17.2568h7.3653c11.3828,0,20.0136-7.3633,20.0136-17.9297,0-12.1269-9.4492-17.9306-20.6836-17.9306"/>
                                                            </g>
                                                        </g>
                                                    </g>
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
                            <div class="flex overflow-x-hidden justify-center flex-col border-t border-r border-b border-solid border-gray-200 p-6 w-full text-gray-600 md:w-1/2 md:p-8">
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

                                <button class="py-2 pt-3 pb-3 my-0 mr-0 text-sm leading-5 text-center text-white normal-case bg-black bg-none rounded border-0 border-gray-500 border-solid cursor-pointer"
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
                                            <input hidden name="duration" value="{getServiceDuration(selectedServiceId)}"/>
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
                                                                </svg>
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
    :global(.std-calendar-wrap) {
        width: 400px !important;
        height: 100% !important;
        border-color: transparent !important;
    }

    :global(.sdt-calendar.svelte-hexbpx.svelte-hexbpx.svelte-hexbpx) {
        width: 100% !important;
        height: 100% !important;
    }

    :global(.is-selected.svelte-hexbpx .std-btn.svelte-hexbpx.svelte-hexbpx,
        .is-selected.in-range.svelte-hexbpx .std-btn.svelte-hexbpx.svelte-hexbpx) {
        border-radius: 100px !important;
    }

    :global(.std-btn.svelte-hexbpx.svelte-hexbpx.svelte-hexbpx) {
        padding: 0 !important;
    }

    :global(.sdt-btn-day.svelte-hexbpx.svelte-hexbpx.svelte-hexbpx) {
        max-height: 45px !important;
        height: 45px !important;
        width: 45px !important;
    }

    :global(.std-btn.svelte-hexbpx.svelte-hexbpx.svelte-hexbpx:hover) {
        border-radius: 100px !important;
    }

    :root {
        /* general */
        --sdt-bg-main: transparent; /** wrap background color */
        --sdt-shadow-color: transparent; /** wrap shadow color */
        --sdt-wrap-shadow: 0; /** wrap shadow settings */
        --sdt-radius: 4px; /** wrap radius */
        --sdt-color: var(--app-color--blue); /** data to select(e.g date/time) text color (except header & buttons) */
        --sdt-color-selected: #fff; /** selected data(e.g date/time) text color */
        --sdt-header-color: #000; /** header items color (e.g. text & buttons) */
        --sdt-header-btn-bg-hover: var(--app-color-blue--light); /** header items hover background color */
        --sdt-bg-selected: var(--app-color--blue); /** selected data(e.g date/time) background color */

        /* action buttons */
        --sdt-today-bg: var(--app-color--blue); /** date picker today button hover background color */
        --sdt-today-color: #eeeded; /** date picker today button text & border color */
        --sdt-clear-color: #dc3545; /** clear button text & border color */
        --sdt-clear-bg: transparent; /** clear button background color */
        --sdt-clear-hover-color: var(--sdt-bg-main); /** clear button hover text color */
        --sdt-clear-hover-bg: #dc3545; /** clear button hover background color */

        /* time picker */
        --sdt-clock-selected-bg: var(--sdt-bg-selected); /** selected time background color */
        --sdt-clock-bg: #eeeded; /** time picker inner circle background color */
        --sdt-clock-color: var(--sdt-color); /** time picker text color (watch "--sdt-color") */
        --sdt-clock-color-hover: var(--sdt-color); /** time picker hover text color (watch "--sdt-color") */
        --sdt-clock-time-bg: transparent; /** time picker time background color */
        --sdt-clock-time-bg-hover: transparent; /** time picker time selection hover background color */
        --sdt-clock-disabled-time: #b22222; /** disabled time picker time text color */
        --sdt-clock-disabled-time-bg: #eee; /** disabled time picker time background color */

        /* date picker */
        --sdt-table-selected-bg: var(--app-color--light); /** selected date background color */
        --sdt-table-disabled-date: var(--app-color--light); /** disabled dates text color */
        --sdt-table-disabled-date-bg: #eee; /** disabled dates background color */
        --sdt-table-bg: transparent; /** date picker inner table background color */
        --sdt-table-data-bg-hover: #eee; /** table selection data hover background color */
        --sdt-table-today-indicator: #ccc; /** date picker current day marker color */

    }
</style>
