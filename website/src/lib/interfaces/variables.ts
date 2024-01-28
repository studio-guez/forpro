export interface Variables {
    readonly BASE_CMS_URL: string;
    readonly BASE_EASYAPPOINTMENTS_URL: string;
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
}
