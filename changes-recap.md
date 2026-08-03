# Changes recap

This document summarizes the major changes between `main` (`fd29553`, May 1, 2026)
and this branch (`7b91d46`, August 3, 2026).

## At a glance

- 90 commits
- 947 files changed before this recap
- 28,311 insertions and 175,750 deletions
- The platform now consists of Kirby CMS, two SvelteKit frontends, and a Nuxt
  menu application, all orchestrated through Docker Compose and Traefik.

## Stack upgrades

### CMS

- Upgraded Kirby from 4 to 5 and moved the supported PHP range from 8.1–8.3 to
  8.3–8.4. The Docker images now run PHP 8.4 with Apache.
- Stopped committing the Kirby core. Kirby and the rest of the PHP dependencies
  are now installed reproducibly from `composer.json` and `composer.lock`.
- Split the former CMS Dockerfile into development and production images, added
  explicit Apache and PHP configuration, and streamlined the entrypoint.
- Added Node.js, system Chromium, and Puppeteer support to the CMS images for
  FoodLab PDF generation through Browsershot.
- Added or centralized Composer dependencies for QR codes, Schema.org data,
  Browsershot, Google APIs, and iCalendar support.
- Moved sensitive Kirby configuration to environment variables, with a safe
  `.env.example` for local setup.

### Website and restaurant frontends

- Upgraded Svelte from 4.2 to 5.56, SvelteKit from 2.4 to 2.70, the Node adapter
  from 4 to 5, Vite from 5 to 7, TypeScript from 5.0 to 5.9, and the associated
  linting and formatting toolchain.
- Standardized development and production images on Node 24 and pnpm.
- Added pnpm workspace configuration, build-script allowlists, dependency-age
  controls, and audited lockfile updates.
- Migrated the website from Tailwind CSS 3 and component SCSS to Tailwind CSS 4
  with the Vite integration. The restaurant remains on the updated Tailwind 3
  stack.

### Menu application

- Integrated the formerly separate menu project directly into this repository
  under `menu/`.
- Integrated it on Nuxt 4, Node 24, and pnpm, with dedicated development and
  production Docker images.
- Added FoodLab and FoodCourt screen routes, CMS-backed menu data, responsive
  scaling utilities, static assets, and deployment configuration.
- Added the menu service to development and production Compose stacks and
  exposed its CMS base URL through Nuxt runtime configuration.

## SEO improvements

- Consolidated SEO management on the Kirby SEO plugin and removed the older,
  overlapping `kirby-king-dedede` implementation.
- Added a CMS metadata adapter that resolves Kirby SEO's page-to-site fallback
  cascade and exposes consistent title, description, canonical URL, robots,
  locale, Open Graph, Twitter, and Schema.org data to the frontend.
- Added a shared `AppSeo` component so CMS-driven website routes render complete
  metadata consistently instead of implementing partial tags page by page.
- Added Schema.org JSON-LD:
  - `WebPage` data for CMS-driven website pages.
  - `Restaurant` data for the FoodLab frontend.
- Replaced the website's hand-built sitemap with a proxy to the sitemap generated
  by Kirby SEO, preserving canonical `for-pro.ch` URLs.
- Added a dynamic website `robots.txt` proxy and a restaurant sitemap endpoint.
- Expanded page-level meta tags with canonical, robots, Open Graph URL/type/
  locale/image, and Twitter card information.

## Deprecated and removed modules

### Calendar and booking

- Removed the unused `kirby-calendars` plugin in full, including its data models,
  Panel views, dialogs, permissions, mail templates, routes, and build files.
- Removed the corresponding CMS booking tab, appointment block, calendar user
  role, permissions, environment configuration, and deployment setup.
- Removed the website booking experience, including rendez-vous pages,
  confirmation routes, slot APIs, interfaces, and supporting utilities.

This deprecation touched 100 files and removed about 9,844 lines.

### Other module cleanup

- Removed `kirby-king-dedede`; its SEO responsibilities are now handled by the
  maintained `kirby-seo` integration.
- Dissolved the `kirby-forpro` plugin wrapper. Reusable ForPro blueprints, models,
  templates, and routes now live directly in `cms/site`, while obsolete booking
  pieces were removed.
- Removed the unused `@sveltejs/adapter-static` dependency from the restaurant.
- Removed legacy Bun and CMS command-script artifacts.

## Repository and deployment cleanup

- Removed 581 committed Kirby core files (about 135,000 lines); Composer now owns
  this dependency and `cms/kirby/` and `cms/vendor/` are ignored.
- Removed the obsolete `docs/content.zip` archive and the now-empty top-level
  `docs` directory.
- Reworked Compose mounts so source and persistent CMS state have clear
  ownership. Production images contain application code while content, media,
  accounts, sessions, caches, and plugin data remain host-managed.
- Replaced anonymous frontend dependency mounts with named `node_modules`
  volumes and aligned container users with host ownership in development.
- Added Docker build-context exclusions and removed unused named volumes,
  packages, imports, styles, comments, and debug logging.
- Expanded the README with the current architecture, local setup, upgrade and
  audit workflows, troubleshooting, content synchronization, and deployment
  instructions.

## Other notable improvements

- Added an `image-guard` CMS plugin to downscale oversized uploads and convert
  CMYK JPEGs before Kirby thumbnail generation, plus a cleanup command for
  existing content.
- Updated Kirby Panel plugins for Kirby 5 component and notification APIs.
- Improved FoodLab menu/PDF routes and permissions.
- Added more robust frontend API error handling and user-facing error responses.
- Improved accessibility with missing alternative text, semantic roles, and
  keyboard interactions.
