// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    ssr: false,
    app: {
        baseURL: '/'
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
