import { PUBLIC_ENVIRONMENT } from '$env/static/public';

// Deployment target, baked in at build time (see Dockerfile.prod / CI).
// Preprod must stay out of search engines and out of analytics.
export const IS_PREPROD = PUBLIC_ENVIRONMENT === 'preprod';
