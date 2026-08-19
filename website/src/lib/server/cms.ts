import { env } from '$env/dynamic/private';
import { PUBLIC_CMS_BASE_URL } from '$env/static/public';

// Server-side fetches go straight to the cms container over the Docker network
// (CMS_INTERNAL_URL, e.g. http://cms) instead of hairpinning through the public
// domain. Falls back to the public URL when unset (dev, bare `node build`).
export const CMS_SERVER_BASE_URL = (env.CMS_INTERNAL_URL || PUBLIC_CMS_BASE_URL).replace(/\/$/, '');
