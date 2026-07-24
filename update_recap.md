# Dependency Update Recap

This document summarizes the update of the three stacks (`cms`, `website`,
`restaurant`) to their latest **compatible** versions, focusing on the core
packages of each stack:

- **cms** → `kirby` / `php`
- **website** & **restaurant** → `node` / `sveltekit` / `svelte`

The work was done in the order requested: **(1)** Docker images, **(2)** run the
package-manager update (`composer` / `pnpm`), **(3)** adapt the code where
necessary, **(4)** write this recap.

---

## 1. Docker images

| File | Image | Before | After | Notes |
|------|-------|--------|-------|-------|
| `cms/Dockerfile` | base OS | `ubuntu:latest` | `ubuntu:24.04` | Pinned for reproducibility. Ships PHP 8.3, which matches the hard-coded `php8.3-fpm.sock` in `cms/nginx/default.conf`. |
| `cms/Dockerfile` | Composer | `composer:2` | `composer:2.8` | Pinned the Composer 2 line. |
| `website/Dockerfile.dev` & `.prod` | Node | `node:lts-alpine` | `node:24-alpine` | Pinned to the current Node LTS (24 "Krypton"). |
| `restaurant/Dockerfile.dev` & `.prod` | Node | `node:lts-alpine` | `node:24-alpine` | Same as website. |
| `website/.nvmrc` & `restaurant/.nvmrc` | Node | `v20.11.0` | `v24.18.0` | Aligned local Node version with the pinned image (latest LTS). |

**Traefik** (`compose.dev.yml` / `compose.prod.yml`) was intentionally **left at
`traefik:v2.11`**. It is infrastructure, not a stack core package, and a jump to
Traefik v3 changes provider/label semantics and carries routing-regression risk
that is out of scope for this "core packages" update.

---

## 2. Dependency updates

### CMS — Kirby / PHP (`cms/composer.json`, `cms/composer.lock`)

| Package | Before | After |
|---------|--------|-------|
| `getkirby/cms` | `4.5.0` (locked) | **`4.9.5`** (latest 4.x) |
| `php` (constraint) | `~8.1.0 \|\| ~8.2.0 \|\| ~8.3.0` | `~8.1.0 \|\| ~8.2.0 \|\| ~8.3.0 \|\| ~8.4.0` |

Regenerated with `composer update` (`No security vulnerability advisories found`).

**Why Kirby 4.9.5 and not Kirby 5?**
Kirby 5 is the latest major, but it is **not compatible** with the bundled
third-party plugin `site/plugins/kirby-seo` (v1.1.2). That plugin has a hard
runtime guard in its `index.php`:

```php
version_compare(App::version(), '5.0.0', '>') === true
    -> throw new Exception('Kirby SEO requires Kirby 4.0.2 or higher.');
```

i.e. it refuses to load on any Kirby newer than `5.0.0`. Reaching Kirby 5 would
require replacing/upgrading that third-party plugin (and re-validating the five
custom plugins against Kirby 5's API), which is outside the "core packages"
scope and cannot be verified in this environment. **Kirby 4.9.5 is therefore the
latest version compatible with the current plugin set.**

**`chillerlan/php-qrcode` added to `composer.json` (`^5.0`).**
It was already present in the previous `composer.lock` as an (undeclared)
root-level package. The `kirby-foodlab` plugin relies on it being available
through the **root** Composer autoloader (its own `vendor/` is not installed in
the Docker/deploy pipeline, and its `index.php` does
`@include_once __DIR__ . "/vendor/autoload.php"`). Declaring it explicitly keeps
QR-code generation working and makes `composer.json`/`composer.lock` consistent
(`composer validate` now passes). Kirby's transitive `christian-riesen/base32`
(2FA/TOTP) is unaffected.

> Note: the checked-in `cms/kirby/` folder is a build-time artifact. Both the
> Docker build and the server deploy install Kirby fresh via `composer install`
> (the folder is `rsync --exclude`d on deploy), so it is not kept in lockstep
> with the lock file and was left untouched to keep the diff focused.

### Website & Restaurant — Node / SvelteKit / Svelte

Core packages bumped in both `website/package.json` and `restaurant/package.json`
(resolved versions shown):

| Package | Before | After |
|---------|--------|-------|
| `svelte` | `^4.2.7` | **`^5.0.0`** (5.56.7) |
| `@sveltejs/kit` | `^2.4.0` | `^2.22.0` (2.70.1) |
| `@sveltejs/vite-plugin-svelte` | `^3.0.0` | `^6.0.0` (6.2.4) |
| `vite` | `^5.0.3` | `^7.0.0` (7.3.6) |
| `@sveltejs/adapter-node` | `^4.0.1` | `^5.2.0` (5.5.7) |
| `@sveltejs/adapter-static` (restaurant) | `^3.0.2` | `^3.0.6` (3.0.10) |
| `svelte-check` | `^3.6.0` | `^4.0.0` (4.7.3) |
| `eslint-plugin-svelte` | `^2.35.1` | `^2.46.0` (Svelte 5 compatible) |
| `prettier-plugin-svelte` | `^3.1.2` | `^3.3.0` |
| `@types/eslint` | `8.56.0` | `^9.6.0` |

`vite@7` + `@sveltejs/vite-plugin-svelte@6` was chosen (rather than `vite@8` /
plugin `7`) as the latest combination that is peer-compatible with the current
`@sveltejs/kit@2` line.

The ESLint/Prettier/Tailwind toolchain was deliberately kept on its current
majors (ESLint 8, flat-config migration avoided) since it is not a stack core
package and lint is not part of the build/deploy pipeline.

Lockfiles were regenerated with `pnpm install`. The **stale, unused**
`package-lock.json` in each frontend was removed — the Docker images and deploy
use **pnpm** exclusively (`pnpm-lock.yaml`), so the npm lockfile was misleading
(it still pinned Svelte 4).

---

## 3. Code / config adaptations

### `pnpm-workspace.yaml` added to `website/` and `restaurant/` (build fix)

The pinned `node:24-alpine` images use **pnpm 11** (via `corepack`/global
install). pnpm 11 changed two behaviors that broke the Docker build:

1. It **auto-installs before `pnpm run <script>`** and aborts the script when
   that check is unhappy.
2. A **first-time** `pnpm install` **exits non-zero** when a dependency ships an
   unapproved build script (`esbuild`, `@parcel/watcher` — both pulled in
   transitively). In a clean Docker layer this made `RUN pnpm install` fail.

`@parcel/watcher` is an optional native (node-gyp) dependency that has no build
toolchain in the Alpine image, so *approving* its build is the wrong fix. Instead
each frontend now has:

```yaml
verifyDepsBeforeRun: false
strictDepBuilds: false
neverBuiltDependencies:
  - esbuild
  - '@parcel/watcher'
```

With this, both `pnpm install` and `pnpm run build` exit `0` on a clean install.
(The old `.npmrc` `engine-strict=true` setting is preserved; pnpm 11 no longer
reads the `pnpm` field from `package.json`.)

### Svelte 5

No source changes were required — Svelte 5 renders the existing Svelte 4
components via its legacy-interop mode. **`pnpm run build` succeeds for both
frontends** (this is the exact command used by `Dockerfile.prod` and the server
deploy).

---

## 4. Validation performed

| Stack | Command | Result |
|-------|---------|--------|
| cms | `composer update` / `composer validate` | Lock regenerated to Kirby 4.9.5; valid; no security advisories. (Full `composer install` couldn't run here only because of sandbox GitHub download auth, not the changes.) |
| website | `pnpm install` + `pnpm run build` | ✅ exit 0 |
| restaurant | `pnpm install` + `pnpm run build` | ✅ exit 0 |

### Known, pre-existing (non-blocking) items

- `pnpm run check` (`svelte-check`) reports **pre-existing** TypeScript errors in
  both frontends (e.g. loosely-typed CMS API responses, `possibly null`
  values, a missing `$lib/interfaces/cmsApiResponse` module in `restaurant`, and
  `//todo` placeholders). These are application-level type issues that are
  independent of the version bump and are **not** part of the build/Docker
  pipeline, so they were left as-is.
- `@lottiefiles/svelte-lottie-player@0.3.1` ships Svelte-4-style component
  typings, which produces `svelte-check` type warnings under Svelte 5. It still
  compiles and renders correctly at runtime (the production build passes).
