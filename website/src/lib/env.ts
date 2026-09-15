import { PUBLIC_ENVIRONMENT } from '$env/static/public';

export const IS_PROD = PUBLIC_ENVIRONMENT === 'production';
