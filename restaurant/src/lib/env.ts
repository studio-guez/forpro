import { PUBLIC_ENVIRONMENT } from '$env/static/public';

// Deployment target, baked in at build time (see Dockerfile.prod / CI).
// Only production is indexed and tracked; dev and preprod must stay out of
// search engines and out of analytics.
export const IS_PROD = PUBLIC_ENVIRONMENT === 'production';
