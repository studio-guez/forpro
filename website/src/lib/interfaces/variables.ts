export interface Variables {
    readonly CMS_BASE_URL: string;
    readonly EASYAPPOINTMENTS_BASE_URL: string;
    readonly EASYAPPOINTMENTS_API_TOKEN: string;
}

export interface Slot {
    readonly date: string;
    readonly time: string;
}

export interface AppointmentDetails {
    readonly date: string;
    readonly time: string;
}

export interface BookingCMSResponse {
    readonly headline: string;
    readonly description: string;
    readonly servicesLabel: string;
    readonly slotsLabel: string;
    readonly bookingConfirmButtonLabel: string;
    readonly bookingNoSlotsLabel: string;
}

export interface Service {
    id: number,
    name: string,
    duration: string,
}

export interface Provider {
    id: number,
    firstName: string,
    lastName: string,
    email: string,
    mobile: string,
    phone: string,
    address: string,
    city: string,
    zip: string,
    notes: string,
    timezone: string,
    language: string,
    services: string[],
    settings: {
        username: string,
        password: string,
        notifications: boolean,
        calendarView: string,
        googleSync: boolean,
        googleCalendar: null,
        googleToken: null,
        syncFutureDays: number,
        syncPastDays: number,
        workingPlan: {
            sunday: null,
            monday: {
                start: string,
                end: string,
                breaks: []
            },
            tuesday: {
                start: string,
                end: string,
                breaks: []
            },
            wednesday: {
                start: string,
                end: string,
                breaks: []
            },
            thursday: {
                start: string,
                end: string,
                breaks: []
            },
            friday: {
                start: string,
                end: string,
                breaks: []
            },
            saturday: null
        }
    }
}

export interface Customer {
    id: number,
    firstName: string,
    lastName: string,
    email: string,
    phone: string,
    city: string,
    zip: string,
    timezone: string,
    languages: string,
    notes: string,
}

export interface Appointment {
    id: number,
    book: string,
    start: string,
    end: string,
    hash: string,
    location: string,
    notes: string,
    customerId: number,
    providerId: number,
    serviceId: number,
    googleCalendarId: null|number
}
