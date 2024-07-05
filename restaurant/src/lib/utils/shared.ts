const headers = new Headers();
headers.append('Content-Type', 'application/json');

export const getHeaders = (): Headers => {
	return headers;
};

const handleError = (errorMsg: string, error: any) => {
	console.error(`${errorMsg}: ${error}`);
};

export const fetchFromAPI = async <T>(request: Request, errorMsg: string): Promise<T> => {
	try {
		const response = await fetch(request);
		const data = await response.json();

		console.log(data);

		return data as T;
	} catch (error) {
		console.log(error);
		handleError(errorMsg, error);
	}
};
