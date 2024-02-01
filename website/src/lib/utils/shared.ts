const headers = new Headers();
headers.append('Content-Type', 'application/json');

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
