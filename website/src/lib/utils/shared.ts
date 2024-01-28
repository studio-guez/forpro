import {EASYAPPOINTMENTS_API_TOKEN} from "$lib/utils/constants";

const headers = new Headers();
headers.append('Content-Type', 'application/json');
headers.append('Access-Control-Allow-Origin', 'http://localhost:8003');
headers.append('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE, OPTIONS');
headers.append('Authorization', `Bearer ${EASYAPPOINTMENTS_API_TOKEN}`);

export const getHeaders = (): Headers => {
    return headers;
}

const handleError = (errorMsg: string, error: any) => {
    console.error(`${errorMsg}: ${error}`);
}

export const fetchFromAPI = async <T>(request: Request, errorMsg: string): Promise<T> => {
    try {
        const response = await fetch(request);
        if (!response.ok) {
            handleError(errorMsg, new Error(errorMsg));
        }
        const data = await response.json();
        console.log(data);
        return data as T;
    } catch (error) {
        handleError(errorMsg, error);
    }
}
