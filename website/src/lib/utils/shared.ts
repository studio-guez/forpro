import type {Cookies} from "@sveltejs/kit";
import {BASE_CMS_URL} from "$lib/utils/constants";

const CSRF_TOKEN_COOKIE_KEY = 'csrftoken';
const SESSION_ID_COOKIE_KEY = 'sessionid';

export const getAuthHeaders = (cookies: Cookies) => {
    // Use optional chaining to protect against null or undefined values
    const csrfToken = cookies.get(CSRF_TOKEN_COOKIE_KEY);
    const sessionId = cookies.get(SESSION_ID_COOKIE_KEY);

    const headers: { [key: string]: string } = {
        'Content-Type': 'application/json',
        'X-CSRFToken': csrfToken || '',
    };

    if (sessionId) {
        headers['Authorization'] = `Token ${sessionId}`;
    }

    return headers;
};

export const fetchAPI = async (url: string, method: string, cookies: Cookies, body?: any) => {
    try {
        const response = await fetch(url, {
            method: method,
            credentials: 'include',
            headers: getAuthHeaders(cookies),
            body: JSON.stringify(body)
        });

        const jsonResponse = await response.json();

        if (!response.ok) {
            console.error(jsonResponse);
            console.error(response.status);
        }
        return jsonResponse;
    } catch (error) {
        console.error(error);
    }
};

export async function fetchAPIPageContent(slug: string) {
    try {
        const response = await fetch(`${BASE_CMS_URL}${slug}.json`, {
            method: 'GET',
        });

        const jsonResponse = await response.json();

        if (!response.ok) {
            console.error(jsonResponse);
            console.error(response.status);
        }
        return jsonResponse;
    } catch (error) {
        console.error(error);
    }
}
