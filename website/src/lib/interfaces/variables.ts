export interface Variables {
    readonly CMS_BASE_URL: string;
    readonly BOOKING_BASE_URL: string;
}

export interface Slot {
    readonly date: string;
    readonly timezone_type: number;
    readonly timezone: string;
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
    id: string,
    name: string,
    duration: string,
    calendar_id: string
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

export interface Options {
    minDaysBeforeRdvs: number,
    maxEventsPerDay: number,
}
