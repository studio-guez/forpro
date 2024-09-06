import type {Slot} from "$lib/interfaces/variables";

export function getEachDayBetweenTwoDays(startDate: Date, endDate: Date): Date[] {
    const dates: Date[] = []
    const currentDate = new Date(startDate)

    while (currentDate <= endDate) {
        dates.push(new Date(currentDate))
        currentDate.setDate(currentDate.getDate() + 1)
    }

    return dates
}

export async function getSlotsByDate(date: string, calendarId: string, selectedServiceId: string, fetchBinding?: (input: RequestInfo | URL, init?: RequestInit | undefined) => Promise<Response>): Promise<Slot[] | {status: 'error'}> {
    const data = new FormData();
    data.append('calendarId', calendarId);
    data.append('selectedServiceId', selectedServiceId);
    data.append('selectedDate', date);

    const response = fetchBinding ?
        await fetchBinding('/api/booking/get-slots', {
            method: 'POST',
            body: data
        })
        :
        await fetch('/api/booking/get-slots', {
            method: 'POST',
            body: data
        })

    return await response.json();
}

export type Scedules = {
    calendar_id: string,
    closing_hour: string,
    day_id: string,
    id: string,
    is_closed: boolean,
    opening_hour: string,
}

export async function getEachSlotByDayBetweenTwoDates(startDate: Date, endDate: Date, calendarId: string, selectedServiceId: string, fetchBinding?: (input: RequestInfo | URL, init?: RequestInit | undefined) => Promise<Response>)
    : Promise<Awaited<{
    date: Date;
    formattedDate: string;
    value: Slot[] | { status: "error" }
}>[]> {

    const dates: Date[] = getEachDayBetweenTwoDays(startDate, endDate)

    const slotByDatePromises: Promise<{
        date: Date;
        formattedDate: string;
        value: Slot[] | { status: "error" }
    }>[] = dates.map(async (date) => {
        const year = date.getFullYear()
        const month = String(date.getMonth() + 1).padStart(2, '0')
        const day = String(date.getDate()).padStart(2, '0')

        const formattedDate = `${year}-${month}-${day}`

        return {
            date: date,
            formattedDate: formattedDate,
            value: await getSlotsByDate(formattedDate, calendarId, selectedServiceId, fetchBinding),
        }
    })

    return await Promise.all(slotByDatePromises)

}
