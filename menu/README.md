# Menu (Nuxt 3)

Daily-menus screens app for the FoodLab and the FoodCourt. Part of the
[ForPro](../README.md) monorepo — see the root README for setup, Docker and
deployment instructions.

Client-only SPA (`ssr: false`) that fetches the CMS site routes exposed by the
`kirby-menu-du-jour` plugin:

- `GET {cmsBaseUrl}/foodlab`
- `GET {cmsBaseUrl}/foodcourt`
- `GET {cmsBaseUrl}/slider-images`

`cmsBaseUrl` is the public runtime config declared in `nuxt.config.ts`, overridable
with the `NUXT_PUBLIC_CMS_BASE_URL` environment variable. Because the app is a SPA,
the value is baked in at build time, so it must also be set when building for
production (`compose.prod.yml` passes it as a build arg).

## Pages

| Route                         | Screen                     |
| ----------------------------- | -------------------------- |
| `/`                           | FoodLab menu               |
| `/foodCourt`                  | FoodCourt menu             |
| `/foodCourt_screen_main`      | FoodCourt main screen      |
| `/foodCourt_stations_screens` | FoodCourt stations screens |

## Local development

Run it through Docker with the rest of the stack, then open http://menu.localhost:

```bash
docker compose -f compose.dev.yml up -d --build menu
```

The package manager is **npm** (`package-lock.json`), unlike the SvelteKit apps which
use pnpm.

## Menu source files (historical)

The menus used to be read from XLSX files before moving to the CMS:

- https://nextcloud.for-pro.ch/s/nzwDkxkppBQQXiQ/download/Menu_FoodCourt.xlsx
- https://nextcloud.for-pro.ch/s/eG6z2nARQ8JnGqy/download/Menu_FoodLab.xlsx
- https://nextcloud.for-pro.ch/s/bHjZSbAFRkkorGx/download/Menu_FoodCourt.xlsx
- https://nextcloud.for-pro.ch/s/mL7CSBZysgGqawz/download/Menu_FoodLab_2.xlsx
- https://hosting.for-pro.ch/foodcourt.xlsx
- https://hosting.for-pro.ch/foodlab.xlsx
