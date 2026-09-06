import { defineEventHandler, getRequestHeader, setResponseHeader, setResponseStatus } from '#imports'

// Optional HTTP Basic auth, used to keep preprod off the public web.
// Set BASIC_AUTH=user:password (see shared/deploy.env); leaving it unset —
// as on production — makes this middleware a no-op.
// /health stays open so the container healthcheck keeps working.
const expected = process.env.BASIC_AUTH
    ? `Basic ${Buffer.from(process.env.BASIC_AUTH).toString('base64')}`
    : ''

export default defineEventHandler((event) => {
    if (!expected || event.path === '/health') return
    if (getRequestHeader(event, 'authorization') === expected) return

    setResponseStatus(event, 401)
    setResponseHeader(event, 'www-authenticate', 'Basic realm="Restricted", charset="UTF-8"')
    setResponseHeader(event, 'cache-control', 'no-store')
    return 'Authentication required'
})
