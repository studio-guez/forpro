import { defineEventHandler, setResponseHeader } from '#imports'

// Liveness probe for the container healthcheck: exempt from Basic auth
// (see server/middleware/basic-auth.ts) and independent of the CMS.
export default defineEventHandler((event) => {
    setResponseHeader(event, 'cache-control', 'no-store')
    return 'ok'
})
