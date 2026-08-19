// https://nuxt.com/docs/api/configuration/nuxt-config

// Deployment target, baked in at build time (see Dockerfile.prod / CI).
// Preprod must stay out of search engines.
const environment = process.env.NUXT_PUBLIC_ENVIRONMENT || 'production'
const isPreprod = environment === 'preprod'

export default defineNuxtConfig({
    ssr: false,
    app: {
        baseURL: '/',
        head: {
            charset: 'utf-8',
            viewport: 'width=device-width, initial-scale=1',
            title: 'ForPro — Menus',
            meta: [
                { name: 'description', content: 'Menus de la semaine — ForPro' },
                ...(isPreprod ? [{ name: 'robots', content: 'noindex, nofollow' }] : []),
            ],
            link: [
                { rel: 'sitemap', type: 'application/xml', href: '/sitemap.xml' },
            ],
        },
    },
    compatibilityDate: '2025-07-01',
    devtools: {enabled: true},
    runtimeConfig: {
        public: {
            // Override with NUXT_PUBLIC_CMS_BASE_URL (see compose.dev.yml / compose.prod.yml)
            cmsBaseUrl: 'https://api.for-pro.ch',
            environment
        }
    },
    css: [
        '~/assets/_main.scss'
    ],
    imports: {
        autoImport: false,
    }
})
