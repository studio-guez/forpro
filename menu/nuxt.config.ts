// https://nuxt.com/docs/api/configuration/nuxt-config
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
            ],
            link: [
                { rel: 'sitemap', type: 'application/xml', href: '/sitemap.xml' },
            ],
        },
    },
    compatibilityDate: '2024-04-03',
    devtools: {enabled: true},
    runtimeConfig: {
        public: {
            // Override with NUXT_PUBLIC_CMS_BASE_URL (see compose.dev.yml / compose.prod.yml)
            cmsBaseUrl: 'https://api.for-pro.ch'
        }
    },
    css: [
        '~/assets/_main.scss'
    ],
    imports: {
        autoImport: false,
    }
})
