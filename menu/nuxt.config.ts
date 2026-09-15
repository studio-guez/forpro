
// Must be set at build time: the noindex meta below is part of the static head (runtime would only change robots.txt).
const environment = process.env.NUXT_PUBLIC_ENVIRONMENT || 'production'
const isProd = environment === 'production'

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
                ...(isProd ? [] : [{ name: 'robots', content: 'noindex, nofollow' }]),
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
