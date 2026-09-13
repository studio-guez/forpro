# Changes recap

This document summarizes the major changes between `main` (`fd29553`, May 1, 2026)
and this branch (`436bbbf`, September 13, 2026).

## At a glance

- 1,052 commits
- 1,447 files changed before this recap
- 53,117 insertions and 182,858 deletions
- The platform now consists of Kirby CMS, two SvelteKit frontends (website and
  restaurant), and a Nuxt menu application, all orchestrated through Docker
  Compose and Traefik.

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

## Restaurant (FoodLab plugin)

The content the restaurant team edits is now grouped in the FoodLab plugin and
kept apart from the website content. The sections below cover only that part;
the website changes follow in the next section.

### Restaurant content moved into the plugin

- Removed the "Restaurant" tab from the website's site settings. The restaurant
  information (texts, opening hours, images, files, links) is now edited in a
  dedicated **Restaurant** area of the Panel, next to the existing **Menu**
  area, and stored by the plugin itself.
- Added a migration script that moves the existing restaurant content from the
  site settings into the plugin, including its media.
- Added purpose-built Panel fields for restaurant images, downloadable files,
  and links, with a link dialog adapted to restaurant content.
- The restaurant form saves automatically while editing, with the possibility
  to cancel.
- Only users with the FoodLab permission can see and edit restaurant content;
  the Panel menu entry is hidden for everyone else.

### Menus and PDFs

- Reworked the menu and PDF generation routes and their permissions.
- Added a required "pop-up menu" PDF alongside the published menu PDF, served
  through the restaurant frontend.
- Updated the plugin's Panel components (menu view, special menu view,
  notifications) for Kirby 5.

### Restaurant frontend and menu screens

- Added Schema.org `Restaurant` structured data, a sitemap endpoint and an
  editable `robots.txt` to the restaurant frontend.
- Added ARIA roles and alternative texts on the restaurant page, and more robust
  error handling when the CMS is unreachable.
- Fixed the week reference used by the FoodLab and FoodCourt screens to pick the
  current week's menu.

## Website

The website itself was rebuilt as planned. This section only highlights the
three transversal improvements that came with it.

### Accessibility improvements

- Added a "skip to content" link and made the main content focusable, so
  keyboard and screen reader users can bypass the navigation.
- Menus, dropdowns, FAQ questions, expandable sections and the search modal
  announce their state to assistive technologies and are fully usable with the
  keyboard, with focus moved and restored when they open and close.
- Search results, filter counts, month navigation, the share button and the
  newsletter form use live regions, so screen readers are told when content
  changes without a page reload.
- Decorative icons are hidden from assistive technologies; sections are
  labelled by their headings; page structure uses semantic elements.
- Images and Lottie animations carry alternative text edited in the
  CMS.
- Animations, smooth scrolling and carousels respect the visitor's
  "reduce motion" preference.

### SEO improvements

- Consolidated SEO management on the Kirby SEO plugin and removed the older,
  overlapping `kirby-king-dedede` implementation.
- Added a CMS metadata adapter that resolves Kirby SEO's page-to-site fallback
  cascade and exposes consistent title, description, canonical URL, robots,
  locale, Open Graph, Twitter, and Schema.org data to the frontend.
- Added a shared `AppSeo` component so CMS-driven website routes render complete
  metadata consistently instead of implementing partial tags page by page.
- Replaced the website's hand-built sitemap with a proxy to the sitemap generated
  by Kirby SEO, preserving canonical `for-pro.ch` URLs.
- Added a dynamic website `robots.txt` proxy driven by the environment.
- Expanded page-level meta tags with canonical, robots, Open Graph URL/type/
  locale/image, and Twitter card information.

#### Structured data (JSON-LD)

Every website page now embeds Schema.org structured data as a single
`application/ld+json` script, so search engines and AI assistants understand
what each page is about rather than only what it says:

- Site-wide: `Organization` (with postal address and social profiles) and
  `WebSite`, emitted once and referenced by every page.
- Every page: `WebPage` (or `FAQPage` for the FAQ) and a `BreadcrumbList`
  reflecting the public URL structure.
- Events: `Event` with dates, times and venue (a new venue field was added to
  the event form).
- Job offers and missions: `JobPosting` with location and posting date.
- Projects: `CreativeWork`, with the collective or people behind it.
- Team: `Organization` with its `Person` members.
- FAQ: `Question` / `Answer` pairs.

The data is assembled by the CMS, which is the only place that knows the public
URLs, so identifiers and links are always correct.

### AI-friendly content (llms.txt and Markdown)

- Added `/llms.txt`, a plain-text map of the website following the
  [llms.txt](https://llmstxt.org) convention: a short presentation of ForPro,
  key facts, and a curated list of the editorial and index pages with a
  one-line description each.
- Every page is also available as Markdown by adding `.md` to its address
  (for example `/entreprendre/mentorat.md`). It is generated from the same
  content as the HTML page, includes the complete lists for index pages, and is
  advertised from the HTML page as an alternate format.
- Editors control the presentation used for AI assistants from an
  "AI Presentation" section in the site's SEO settings.
- Both use the same page set as the sitemap: pages excluded from search engines
  are excluded here too, and the preprod environment publishes no links.

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
- Added a CI/CD pipeline: images are built per service, pushing to `preprod`
  deploys the preprod environment and pushing to `main` (or a version tag)
  deploys production, with health checks on every service.
- Added Docker build-context exclusions and removed unused named volumes,
  packages, imports, styles, comments, and debug logging.
- Expanded the README with the current architecture, local setup, upgrade and
  audit workflows, troubleshooting, content synchronization, and deployment
  instructions.

## Other notable improvements

- Added an `image-guard` CMS plugin to downscale oversized uploads and convert
  CMYK JPEGs before Kirby thumbnail generation, plus a cleanup command for
  existing content and a script to pre-generate thumbnails.
- Added browser caching and compression for media files served by the CMS.
- Added paginated CMS list endpoints so long lists (events, projects, missions)
  load progressively instead of all at once.
- Updated Kirby Panel plugins for Kirby 5 component and notification APIs.
- Added more robust frontend API error handling and user-facing error responses.
