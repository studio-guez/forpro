# Project instructions — forpro

Shared, agent-agnostic rules for this repository. Claude Code (`CLAUDE.md`) and
GitHub Copilot (`.github/copilot-instructions.md`) both import this file.

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
| php / composer / Kirby CLI | `docker compose -f compose.dev.yml exec -T --user www-data cms php <args>` |

**Always pass `--user www-data` to `exec ... cms`.** `exec` defaults to root inside the
container, while Apache/PHP runs as `www-data`. Any script that boots Kirby writes to
`site/cache/` (UUID index, `changes/pages.cache`), and a root-owned file there makes the
Panel fail to save with a 500 — `The file "{site}/cache/.../pages.cache" is not writable`.
Repair with `docker compose -f compose.dev.yml exec -T cms chown -R www-data:www-data /var/www/html/site/cache`
(or just restart the service — `entrypoint.sh` chowns the runtime dirs on every start).

Working directories inside the containers: `/app` for the three frontends,
`/var/www/html` for the CMS (so repo path `cms/utils/Utils.php` is `utils/Utils.php` there).

Examples:

```bash
docker compose -f compose.dev.yml exec -T website pnpm run check
docker compose -f compose.dev.yml exec -T restaurant pnpm run lint
docker compose -f compose.dev.yml exec -T menu pnpm run build
docker compose -f compose.dev.yml exec -T --user www-data cms composer install
docker compose -f compose.dev.yml exec -T --user www-data cms php -r 'echo PHP_VERSION;'
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

## Common commands

All prefixed with `docker compose -f compose.dev.yml exec -T` (see above).

| Task | Command |
| --- | --- |
| Bring the stack up | `docker compose -f compose.dev.yml up -d` |
| Typecheck a SvelteKit app | `exec -T website pnpm run check` (same for `restaurant`) |
| Lint a SvelteKit app | `exec -T website pnpm run lint` (prettier `--check` + eslint) |
| Autoformat | `exec -T website pnpm run format` |
| Production build | `exec -T website pnpm run build` / `exec -T menu pnpm run build` |
| Hit a page payload | `curl http://cms.localhost/pages/<virtualPath>.json` |
| Hit the global payload | `curl http://cms.localhost/global.json` |
| Hit the restaurant payload | `curl http://cms.localhost/api/restaurant` |
| Hit a menu payload | `curl http://cms.localhost/foodlab` (or `/foodcourt`) |

`menu/` has no `check`/`lint` script — `pnpm run build` is the only gate. **There is no test
suite anywhere in this repo** (no vitest, playwright, phpunit): "verify" always means a real
`curl` against the CMS plus `pnpm run check` / `pnpm run build` on the affected frontend.

---

# Project overview

| Path | Stack | Dev URL |
| --- | --- | --- |
| `cms/` | Kirby 5 (PHP, Apache) | http://cms.localhost |
| `website/` | SvelteKit 5 | http://website.localhost |
| `restaurant/` | SvelteKit 5 | http://restaurant.localhost |
| `menu/` | Nuxt 4, client-only SPA (`ssr: false`) | http://menu.localhost |

Routed by Traefik; mail is caught by Mailpit (http://mailpit.localhost).
The frontends consume the CMS as a JSON API — Kirby renders no HTML for them, `/` on the
CMS just redirects to `/panel`.

## How a website page request flows

This is the part that spans the most files; know it before touching routing or templates.

1. SvelteKit has **one catch-all route**, `website/src/routes/[...slug]/`. Every public URL
   lands there. `+layout.server.ts` fetches `global.json` (header, menus, banner, favicon)
   once per request; `+page.server.ts` fetches `pages/<slug>.json`.
2. The CMS route `pages/(:all).json` (`cms/site/config/config.php`) does **not** resolve the
   path as a Kirby page id. It scans `site()->index()` for the page whose **`virtualPath`**
   matches, then renders that page's `json` representation.
3. `virtualPath` comes from the `parent-page` plugin (`cms/site/plugins/parent-page/`). A
   page's public path is its real Kirby ancestors *plus* the chain of its `parentPage` field,
   minus the structural top-level containers. So `content/pages/mentorat` can be published at
   `/entreprendre/mentorat` purely through a field. Consequences:
   - the frontend URL is **not** the Kirby page id — never assume they match;
   - moving a page in the Panel tree does not have to change its URL, and setting
     `parentPage` does change it;
   - `+page.server.ts` 301-redirects when the requested path differs from `page.path`;
   - `frontendUrl()` makes the Panel's preview/open links point at the decoupled frontend.
4. `<template>.json.php` builds the payload from `Utils::` helpers and emits a `template`
   key. `website/src/routes/[...slug]/+page.svelte` switches on that key to pick a component
   from `website/src/lib/components/templates/` (default: `Page.svelte`).
5. Inside a template, the `body` blockbuilder payload is rendered by
   `lib/components/blocks/Blocks.svelte`, which dispatches to the `BlockModule*.svelte`
   components. **Adding a block type is a four-file change**: blueprint under
   `cms/site/blueprints/blocks/`, a mapping in `UtilsBlocks`, a `BlockModule*.svelte`, and a
   branch in `Blocks.svelte` — plus the matching interface in `lib/interfaces/`.

The other two frontends are far simpler: `restaurant/` is a single `+page.server.ts` against
`/api/restaurant`, and `menu/` is four SPA pages against the `kirby-menu-du-jour` site routes.

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

## Shared serialization lives in `cms/utils/`

`Utils.php` is only a composition shell: the implementation is split by concern into traits
under `cms/utils/traits/` (`UtilsMedia`, `UtilsLinks`, `UtilsTaxonomies`, `UtilsSeo`,
`UtilsEmbeds`, `UtilsPages`, `UtilsBlocks`, `UtilsSearch` + `UtilsSearchText`), all `use`d
into the single `Utils` class so callers keep the flat `Utils::` API. A new helper goes into
the trait that owns its concern, not into `Utils.php`.

`*.json.php` templates are thin: they assemble a payload out of `Utils::` helpers, they do
not re-implement serialization. Before writing a mapping in a template, grep
`cms/utils/traits/` — a helper very often already exists (`getJsonEncodeImageDataOrNull()`, `getEventDateFields()`,
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
- Know which kind of route you are adding. Three different registries are in play:
  - **site routes in `config.php`** — `global.json`, `search.json`, `pages/(:all).json`.
  - **plugin site routes** — `kirby-menu-du-jour/routes/index.php`: `/foodcourt`, `/foodlab`,
    `/slider-images`, `/slider-images/(:any)`. Consumed by `menu/`.
  - **plugin API routes** (prefixed `/api/`) — `kirby-foodlab/routes/index.php`:
    `/api/restaurant`, `/api/restaurant/menu/...`. Consumed by `restaurant/`.
    `kirby-menu-du-jour` also registers panel-only API routes under `/api/menu-du-jour/...`.
- `compose.dev.yml` bind-mounts individual paths under `cms/`, not the whole directory.
  A scratch script dropped in `cms/` is invisible inside the container — put it in
  `cms/utils/` (which is `/var/www/html/utils/` there).
- To verify a change, boot Kirby headlessly in the container (as `www-data`, see above):
  `docker compose -f compose.dev.yml exec -T --user www-data cms php -r '... new Kirby\Cms\App(["roots" => ["index" => "/var/www/html"]]) ...'`
- Always verify option/config behaviour with a real request (`curl`), not code reading —
  several of these failure modes look correct in one environment and break in another.

---

# Frontend conventions

- **website / restaurant**: fetch the CMS **server-side**, in `+layout.server.ts` /
  `+page.server.ts` loads, so the request can use the internal Docker URL. Never fetch the
  CMS from a `.svelte` component.
- **menu**: the opposite — it is a client-only SPA (`ssr: false`), so its pages fetch in
  `onMounted` through the `menu/composables/*Data.ts` helpers, which hit the public
  `getCmsBaseUrl()`. There is no server load and no `CMS_INTERNAL_URL` here.
- `menu/nuxt.config.ts` sets `imports.autoImport: false`: Vue and Nuxt symbols must be
  imported explicitly (`from 'vue'`, `from '#imports'`), and so must components,
  composables and utils (`~/utils/...`). Auto-import will not save you.
- Server-side fetches use `CMS_INTERNAL_URL` (`http://cms` in prod) via
  `src/lib/server/cms.ts`, falling back to `PUBLIC_CMS_BASE_URL` in dev.
- All `PUBLIC_*` / `NUXT_PUBLIC_*` variables are **baked in at Docker build time**
  (SvelteKit `$env/static/public`, Nuxt `process.env` -> `runtimeConfig.public`).
  Changing one requires a rebuild, not just a restart. Runtime-only values must go through
  `$env/dynamic/private`.
- `PUBLIC_ENVIRONMENT` drives `IS_PROD` (`src/lib/env.ts` in website and restaurant,
  `isProd` in `menu/nuxt.config.ts`). Only `production` is indexed and tracked: anything
  else gets `noindex, nofollow` and no Matomo. Gate new analytics/robots code on `IS_PROD`,
  not on a preprod check.
- Content can contain non-YouTube "video" blocks (e.g. Instagram embeds), so guard any
  YouTube regex match or SSR will 500.

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
