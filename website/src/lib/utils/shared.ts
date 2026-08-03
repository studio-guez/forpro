const headers = new Headers();
headers.append('Content-Type', 'application/json');

export const getHeaders = (): Headers => {
    return headers;
}

const handleError = (errorMsg: string, error: any) => {
    console.error(`${errorMsg}: ${error}`);
}

export const fetchFromAPI = async <T>(request: Request, errorMsg: string): Promise<T | null> => {
    try {
        const response = await fetch(request);
        if (!response.ok) {
            // A 404 is an expected outcome (unknown slug / short link): let the
            // caller decide what to do with `null` without polluting the logs.
            if (response.status !== 404) {
                handleError(errorMsg, new Error(`${response.status} ${response.statusText} (${request.url})`));
            }
            return null
        }
        const data = await response.json();

        return data as T;
    } catch (error) {
        handleError(errorMsg, error);
        return null
    }
}
