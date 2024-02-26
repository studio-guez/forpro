<script lang="ts">
    import SveltyPicker, {config} from "svelty-picker";
    import {fr} from 'svelty-picker/i18n';

    import BookingSlots from "$lib/components/BookingSlots.svelte";
    import type {Slot} from "$lib/interfaces/variables";

    import { enhance } from "$app/forms";
    import dayjs from "dayjs";
    import {writable} from "svelte/store";
    import {browser} from "$app/environment";

    export let data;
    export let form;

    let step = writable(1);
    form = { appointment: null };

    step.subscribe(() => {
        scrollToTopOfPage()
    })

    function scrollToTopOfPage() {
        if( !browser ) return
        document.querySelectorAll('.s-layout').forEach(value => {
            value.scrollTo({top: 0, behavior: 'smooth'})
        })
    }

    const calendarId = data.calendarId;

    let selectedServiceId: number = data.services ? data.services[0]?.id : null;
    if(data.services.length === 0) console.error('There are no services in the administration')

    let selectedSlotId: number | null = null;
    let selectedDate: string = dayjs().format('YYYY-MM-DD');

    let formattedDate: string | null = null;

    let services = data.services ?? [];
    let schedules = Object.values(data.schedules) ?? [];

    let slots: Slot[] = data.availabilities;
    let loading: boolean = false;

    let minDaysBeforeAppointment = data.options.minDaysBeforeRdvs ?? 1;
    let today = dayjs();
    let startDate = today.add(minDaysBeforeAppointment, 'day').toDate();

    const endDate = today.add(1, 'month').toDate();
    config.i18n = fr;

    const handleDateSelection = async (event) => {
        selectedDate = event.detail;

        if(selectedDate == null) {
            selectedSlotId = null;
            return;
        }

        const data = new FormData();
        data.append('calendarId', calendarId);
        data.append('selectedServiceId', selectedServiceId);
        data.append('selectedDate', selectedDate);

        const response = await fetch('/api/booking/get-slots', {
            method: 'POST',
            body: data
        });

        slots = await response.json();

        formattedDate = new Date(event.detail).toLocaleDateString('fr-CH');
    }

    const getServiceName = (id: number) => {
        return services.find(service => service.id === id).name;
    }

    const getServiceDuration = (id: number) => {
        return services.find(service => service.id === id).duration;
    }

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    };

    function disableDays(date) {
        if (schedules.length > 0) {
            return schedules.filter(schedule => schedule.is_closed && schedule.day_id == date.getDay()).length === 1;
        } else {
            return false;
        }
    }
</script>

<div class="h-full mx-auto w-full lg:max-w-4xl text-gray-700 flex flex-col justify-between max-h-300">
    <div class="relative flex flex-col flex-grow h-full overflow-hidden rounded-b md:rounded">
        <div class="flex items-center w-full leading-6 text-black border-b-0 border-solid md:p-4 border-x-0">
            <div class="flex flex-col justify-between mx-auto w-full h-full text-gray-700 bg-white lg:max-w-4xl md:rounded">
                <div class="flex overflow-hidden relative flex-col flex-grow h-full rounded-b md:rounded">
                    <div class="flex flex-wrap h-full">
                        {#if $step === 1}
                            <!-- left side -->
                            <div class="bg-[var(--app-color-beige)] overflow-y-auto overflow-x-hidden py-2 px-4 w-full h-full text-center rounded-b md:w-1/2 md:rounded md:py-8">
                                <div class="flex flex-col justify-evenly min-h-full">

                                    <div>
                                        <div class="mt-4 sm:mt-2">
                                            <div class="inline-block overflow-hidden w-20 h-20 bg-gray-100 bg-cover rounded-full">
                                                <img src="/favicon.svg" alt="icon" />
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
                                                disableDatesFn={disableDays}
                                                on:change={handleDateSelection}
                                                endDate={endDate}
                                                startDate={startDate}
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
                                    {#each services as service, index}
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
                                        on:click={() => step.set(2)}
                                >
                                    {data.content.bookingSlotConfirmationLabel}
                                </button>
                            </div>
                        {:else if $step === 2}
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
                                            {getServiceName(selectedServiceId)}, le {formattedDate} à {formatDate(selectedSlotId.date)}
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
                                        <form method="POST" class="flex flex-col flex-1 justify-between mt-6 w-full" use:enhance on:submit={() => scrollToTopOfPage()}>
                                            <input hidden name="serviceId" value={selectedServiceId}/>
                                            <input hidden name="duration" value="{getServiceDuration(selectedServiceId)}"/>
                                            <input hidden name="calendarId" value={calendarId}/>
                                            <input hidden name="slot" value={selectedSlotId.date}/>
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
                                                                       placeholder="Prénom"
                                                                       class="block py-2 px-3 m-0 w-full text-base bg-white rounded-md border border-gray-300 border-solid appearance-none cursor-text sm:text-sm sm:leading-5 focus:border-blue-600 focus:outline-offset-2"
                                                                />
                                                            </div>
                                                            {#if form?.errors?.firstname}
                                                                <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                                                                    <span class="font-medium">Oops!</span> le prénom est nécessaire.
                                                                </p>
                                                            {/if}
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
                                                                       placeholder="Nom"
                                                                       class="block py-2 px-3 m-0 w-full text-base bg-white rounded-md border border-gray-300 border-solid appearance-none cursor-text sm:text-sm sm:leading-5 focus:border-blue-600 focus:outline-offset-2"
                                                                       value=""
                                                                />
                                                            </div>
                                                        </div>
                                                        {#if form?.errors?.lastname}
                                                            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                                                                <span class="font-medium">Oops!</span> le nom est nécessaire.
                                                            </p>
                                                        {/if}
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
                                                    {#if form?.errors?.email}
                                                        <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                                                            <span class="font-medium">Oops!</span> l'email est nécessaire.
                                                        </p>
                                                    {/if}
                                                    <div class="mt-5">
                                                        <div class="block">
                                                            <label class="flex text-sm font-semibold leading-5 cursor-default"
                                                                   for="email">Téléphone *</label>
                                                        </div>
                                                        <div class="relative mt-1 rounded-md"
                                                             style="box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px; list-style: outside;"
                                                        >
                                                            <input name="phone" type="tel" placeholder="+41(0)791232442"
                                                                   class="block py-2 px-3 m-0 w-full text-base bg-white rounded-md border border-gray-300 border-solid appearance-none cursor-text sm:text-sm sm:leading-5 focus:border-blue-600 focus:outline-offset-2"
                                                                   value="">
                                                        </div>
                                                        {#if form?.errors?.phone}
                                                            <p class="mt-2 text-xs text-red-600 dark:text-red-500">
                                                                <span class="font-medium">Oops!</span> le numéro de téléphone est nécessaire.
                                                            </p>
                                                        {/if}
                                                    </div>
                                                    <div class="mt-5">
                                                          <label for="subject" class="block mb-2 text-sm font-medium text-black">
                                                            Sujet *
                                                          </label>
                                                          <select id="subject"
                                                                  name="subject"
                                                                  class="border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                                                {#each data.content.bookingAppointmentSelect as select}
                                                                    <option value={ select.id }>{ select.label }</option>
                                                                {/each}
                                                          </select>
                                                    </div>
                                                    <div class="flex bottom-0 left-0 flex-col flex-shrink-0 pb-6 mt-6 w-full text-right sm:mt-0 sm:pt-4">
                                                        <div class="flex justify-between w-full">
                                                            <button
                                                                    class="flex items-center py-2 px-6 m-0 text-sm leading-5 text-center normal-case bg-white bg-none rounded border border-gray-500 border-solid cursor-pointer"
                                                                    type="button"
                                                                    on:click={() => {step.set(1)}}
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

                                    <div class="app-page-simple_content" >
                                        <h1 style="color: var(--app-color--green)">
                                            Merci&nbsp;!
                                        </h1>
                                        <h5 style="color: var(--app-color--blue)">
                                            Encore une étape…
                                        </h5>
                                        <p>{data.content.bookingAppointmentSuccessLabel}</p>
                                        <a class="app-button app-button--rounded"
                                           href="/"
                                        >Retourner à la page d'accueil</a>
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
