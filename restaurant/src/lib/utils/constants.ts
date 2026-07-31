import { PUBLIC_CMS_BASE_URL, PUBLIC_MENU_BASE_URL } from '$env/static/public';
import type { Variables } from '$lib/interfaces/variables';

export const variables: Variables = {
	CMS_BASE_URL: PUBLIC_CMS_BASE_URL,
	MENU_BASE_URL: PUBLIC_MENU_BASE_URL
};
