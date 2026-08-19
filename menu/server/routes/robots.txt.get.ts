import { defineEventHandler, setHeader, useRuntimeConfig } from '#imports'

// Served dynamically (instead of public/robots.txt) so preprod can lock crawlers out.
export default defineEventHandler((event) => {
    setHeader(event, 'Content-Type', 'text/plain')

    if (useRuntimeConfig(event).public.environment === 'preprod') {
        return 'User-agent: *\nDisallow: /\n'
    }

    return `User-agent: *
Disallow: /foodCourt_screen_main
Disallow: /foodCourt_stations_screens
Allow: /

Sitemap: https://menus.for-pro.ch/sitemap.xml
`
})
