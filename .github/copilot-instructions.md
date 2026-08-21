# Copilot instructions — forpro

## Never run project toolchains on the host

`pnpm`, `node`, `npx`, `php`, `composer` and `kirby` are **not** meant to be run locally.
Everything runs inside the dev containers defined in `compose.dev.yml`.
Do not run `pnpm install`, `pnpm dev`, `pnpm build`, `php -r`, `composer ...` etc.
directly in the workspace shell, even if the binary happens to exist on the host.

Always prefix with `docker compose -f compose.dev.yml exec`, from the repo root.
Use `-T` when the command is non-interactive (which is always the case for agent runs).

## Service → command mapping

| What you want to run | Command |
| --- | --- |
| pnpm in `website/` | `docker compose -f compose.dev.yml exec -T website pnpm <args>` |
| pnpm in `restaurant/` | `docker compose -f compose.dev.yml exec -T restaurant pnpm <args>` |
| pnpm in `menu/` | `docker compose -f compose.dev.yml exec -T menu pnpm <args>` |
| php / composer / Kirby CLI | `docker compose -f compose.dev.yml exec -T cms php <args>` |

Working directories inside the containers: `/app` for the three frontends,
`/var/www/html` for the CMS (so repo path `cms/utils/Utils.php` is `utils/Utils.php` there).

Examples:

```bash
docker compose -f compose.dev.yml exec -T website pnpm run check
docker compose -f compose.dev.yml exec -T restaurant pnpm run lint
docker compose -f compose.dev.yml exec -T menu pnpm run build
docker compose -f compose.dev.yml exec -T cms composer install
docker compose -f compose.dev.yml exec -T cms php -r 'echo PHP_VERSION;'
```

## If the container is not running

Start it (`docker compose -f compose.dev.yml up -d <service>`) rather than falling back
to the host. For one-off tasks in a stopped service, use `run --rm` instead of `exec`:

```bash
docker compose -f compose.dev.yml run --rm -T website pnpm install
```

For Node tooling that has no service (e.g. rebuilding a Kirby panel plugin bundle),
use a throwaway container, never the host:

```bash
docker run --rm -u "$(id -u):$(id -g)" \
  -v "$PWD/cms/site/plugins/<plugin>:/app" -w /app node:24 \
  node node_modules/.bin/kirbyup src/index.js
```

## Exceptions (host is fine)

`git`, `docker`/`docker compose` themselves, and plain file inspection (`ls`, `grep`, `cat`).

---

# Project overview

| Path | Stack | Dev URL |
| --- | --- | --- |
| `cms/` | Kirby 5 (PHP, Apache) | http://cms.localhost |
| `website/` | SvelteKit 5 | http://website.localhost |
| `restaurant/` | SvelteKit 5 | http://restaurant.localhost |
| `menu/` | Nuxt 3 (screens + public menu pages) | http://menu.localhost |

Routed by Traefik; mail is caught by Mailpit (http://mailpit.localhost).
The frontends consume the CMS as a JSON API.

---

# Kirby / CMS conventions

## camelCase everywhere — never hyphens or underscores

**Always use camelCase for field keys**, in blueprints, in content `.txt` files and in
`$page->field()` calls: `titleHasArrow`, `arrowColor`, `dateStart`, `trackWithMatomo`.

This is not a style preference, it is a correctness requirement: Kirby only *lowercases*
field keys on lookup, it does **not** strip `-` or `_`. So `$page->titleHasArrow()` looks up
`titlehasarrow` and will **never** match a stored `title-has-arrow` or `title_has_arrow`
key — it silently returns an empty field / `false` with no error.

The JSON keys emitted by `*.json.php` templates are a separate contract from the field
names; renaming a field must not change the JSON output the frontends already consume.

Same camelCase rule applies to Svelte/TS/Vue identifiers. Svelte components are
PascalCase files (`Page.svelte`, `AppHeader.vue`).

## Shared serialization lives in `cms/utils/Utils.php`

`*.json.php` templates are thin: they assemble a payload out of `Utils::` helpers, they do
not re-implement serialization. Before writing a mapping in a template, grep `Utils.php` —
a helper very often already exists (`getJsonEncodeImageDataOrNull()`, `getEventDateFields()`,
`resolveCtaStructure(s)()`, `resolveTaxonomyTerms()`, `getEventCardData()`,
`getSeoDataFromPage()`, `getEventProjectBaseData()`, ...). Re-inlining one silently forks the
JSON contract: the two copies drift and only one gets fixed.

- Same shape emitted from two templates -> extract a `Utils::` helper (or reuse the existing
  one, making it `public` if it was `private`).
- Same shape emitted twice inside one template (e.g. two block types building the same
  `cta`/filters payload) -> hoist a local closure, don't copy-paste the array literal.
- Helpers stay generic: pass the `\Kirby\Content\Field` / `Page` / `Files` in, return a plain
  array out. No page-specific branching inside `Utils`.
- Inside `Utils` itself, helpers compose instead of repeating each other: a shared shape gets
  its own `private static` helper the public ones call (`getTaxonomyTermData()` behind
  `resolveTaxonomyTerms()`/`getTaxonomyTerms()`, `taxonomyMatcher()` behind
  `filterStructureByTaxonomy()`/`filterPagesByTaxonomy()`, `getJsonEncodeImageData()` reused by
  `getJsonEncodeMediaData()`). Never duplicate a field mapping between two `Utils::` methods.
- Extracting a helper must not change the emitted JSON keys — verify with a real request
  (`curl http://cms.localhost/pages/<virtualPath>.json`) before and after.

## Kirby gotchas

- In a route handler, use `kirby()->site()`, **not** `$this->site()`. Since Kirby 5.4 the
  latter resolves to `Find::site()`, which requires panel `access` permission and 404s on
  public (`auth => false`) routes.
- Know which kind of route you are adding: `kirby-menu-du-jour` registers **site** routes
  (`/foodcourt`, `/foodlab`, `/slider-images`); `kirby-foodlab` registers **API** routes
  (`/api/restaurant/...`). Booking is the site route `/booking`, not `/api/booking`.
- `compose.dev.yml` bind-mounts individual paths under `cms/`, not the whole directory.
  A scratch script dropped in `cms/` is invisible inside the container — put it in
  `cms/utils/` (which is `/var/www/html/utils/` there).
- To verify a change, boot Kirby headlessly in the container:
  `docker compose -f compose.dev.yml exec -T cms php -r '... new Kirby\Cms\App(["roots" => ["index" => "/var/www/html"]]) ...'`
- Always verify option/config behaviour with a real request (`curl`), not code reading —
  several of these failure modes look correct in one environment and break in another.

---

# Frontend conventions

- Fetch the CMS server-side. website: `+page.server.ts` loads. restaurant: server loads
  (`+page.server.ts`) so it can use the internal Docker URL. menu: `useAsyncData`,
  **never** `onMounted`, for the public pages.
- Server-side fetches use `CMS_INTERNAL_URL` (`http://cms` in prod) via
  `src/lib/server/cms.ts`, falling back to `PUBLIC_CMS_BASE_URL` in dev.
- All `PUBLIC_*` / `NUXT_PUBLIC_*` variables are **baked in at Docker build time**
  (SvelteKit `$env/static/public`, Nuxt `process.env` -> `runtimeConfig.public`).
  Changing one requires a rebuild, not just a restart. Runtime-only values must go through
  `$env/dynamic/private`.
- `PUBLIC_ENVIRONMENT` drives `IS_PREPROD` (`src/lib/env.ts`, `isPreprod` in
  `menu/nuxt.config.ts`): adds `noindex, nofollow` and disables Matomo on preprod.
- Content can contain non-YouTube "video" blocks (e.g. Instagram embeds), so guard any
  YouTube regex match or SSR will 500.
- `menu/` screen routes (`/foodCourt_screen_main`, `/foodCourt_stations_screens`) are
  client-only via `routeRules` + robots.txt; the public routes stay SSR.

---

# Deploy / CI notes

- CI builds GHCR images per service; push to `preprod` deploys preprod, push to `main` or a
  `v*` tag deploys production. Change detection compares against the last successful run on
  the *same* branch.
- `compose.prod.yml` persists only mutable state via bind mounts to `./cms/...`. Never mount
  anything on `/var/www/html` or `site/plugins` — a named volume is seeded from the image
  only when empty and would freeze the code at its first-`up` version.
- `robots.txt`: CMS-side is driven by `KIRBY_ROBOTS_INDEX` in `cms.env` (restart, no
  rebuild); restaurant and menu bind-mount their `robots.txt` from `shared/` on prod.
- `.env*` files are gitignored and seeded from `.example` files on first deploy. Never
  commit secrets, and never print them in terminal output.
