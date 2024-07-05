import {CMS_BASE_URL} from "$lib/utils/constants";

export const getBookingContent = async () => {
    try {
        const response = await fetch(`${CMS_BASE_URL}/api/booking`);
        if (!response.ok) {
            throw new Error('Failed to fetch booking content');
        }
        return response.json();
    } catch (error) {
        console.error('Error fetching booking content:', error);
        throw error;
    }
}
