import { env } from '$env/dynamic/private';
import { PUBLIC_CMS_BASE_URL } from '$env/static/public';

export const CMS_SERVER_BASE_URL = (env.CMS_INTERNAL_URL || PUBLIC_CMS_BASE_URL).replace(/\/$/, '');

export const toInternalUrl = (url: string): string =>
	url.startsWith(PUBLIC_CMS_BASE_URL) ? CMS_SERVER_BASE_URL + url.slice(PUBLIC_CMS_BASE_URL.length) : url;
