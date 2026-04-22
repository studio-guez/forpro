# Technical Documentation

## Stack

```md
├── CMS / API  (cms/)
│   ├── Kirby CMS 4.0
│   ├── PHP >= 8.1
│   └── plugins
│       ├── kirby-calendars        # data → critical
│       ├── kirby-foodlab          # data → critical, needs Chromium + Puppeteer
│       ├── kirby-forpro
│       └── kirby-king-dedede
├── RESTAURANT  (restaurant/)      # SvelteKit 2.0 front-end
└── WEBSITE     (website/)         # SvelteKit 2.0 front-end
```

---

## Run the project locally with Docker

The full stack (CMS, website, restaurant + a Traefik reverse proxy) is
orchestrated by [`compose.dev.yml`](./compose.dev.yml).

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/) and the Docker Compose plugin
  (Docker Desktop already includes it).
- Add the local hostnames to your `hosts` file so the Traefik reverse proxy
  can route the requests:
  - **macOS / Linux:** edit `/etc/hosts` (with `sudo`)
  - **Windows:** edit `C:\Windows\System32\drivers\etc\hosts` (as Administrator)

  Add the following line:
  ```
  127.0.0.1 cms.localhost website.localhost restaurant.localhost
  ```

  > Most browsers resolve `*.localhost` to `127.0.0.1` automatically, but
  > adding the entry above guarantees it works everywhere.

### Start the stack

From the root of the repository:

```bash
docker compose -f compose.dev.yml up --build
```

The first build downloads the base images and installs all dependencies, so
it can take a few minutes. Subsequent starts are much faster.

To run it in the background:

```bash
docker compose -f compose.dev.yml up -d --build
```

### Stop the stack

```bash
docker compose -f compose.dev.yml down
```

Add `-v` if you also want to remove the named volumes (CMS media, cache and
sessions):

```bash
docker compose -f compose.dev.yml down -v
```

### Access the services in the browser

Once the containers are up, open:

| Service             | URL                                              | Notes                                |
| ------------------- | ------------------------------------------------ | ------------------------------------ |
| Website             | <http://website.localhost>                       | SvelteKit dev server (HMR enabled)   |
| Restaurant          | <http://restaurant.localhost>                    | SvelteKit dev server (HMR enabled)   |
| CMS (front)         | <http://cms.localhost>                           | Kirby front-end                      |
| CMS Panel (admin)   | <http://cms.localhost/panel>                     | Kirby admin panel                    |
| Traefik dashboard   | <http://localhost:8888>                          | Reverse-proxy dashboard              |

Direct ports are also exposed in case you prefer to bypass Traefik:

| Service    | Direct URL              |
| ---------- | ----------------------- |
| Restaurant | <http://localhost:5173> |
| Website    | <http://localhost:5174> |
| CMS        | <http://localhost:8000> |

### Live reload / file watching

The `restaurant/` and `website/` source folders are mounted into the
containers, so any code change on your host triggers Vite's hot-module
reload inside the container.

The Kirby CMS source (content, blueprints, plugins, templates, config and
accounts) is also bind-mounted, so edits show up immediately.

### Useful commands

Tail the logs of a single service:

```bash
docker compose -f compose.dev.yml logs -f restaurant
docker compose -f compose.dev.yml logs -f website
docker compose -f compose.dev.yml logs -f cms
```

Open a shell inside a container:

```bash
docker compose -f compose.dev.yml exec cms bash
docker compose -f compose.dev.yml exec restaurant sh
docker compose -f compose.dev.yml exec website sh
```

Rebuild a single service after changing its `Dockerfile` or
`package.json`:

```bash
docker compose -f compose.dev.yml build restaurant
docker compose -f compose.dev.yml up -d restaurant
```

---

## Running services without Docker

If you'd rather run the services directly on your machine you can still use:

### CMS

- Kirby CMS 4.0
- PHP 8.1

```bash
cd cms
php -S localhost:1234 kirby/router.php
```

#### Foodlab plugin (PDF generation) extra dependencies

- Chromium Headless
- Puppeteer

### Website / Restaurant (SvelteKit)

```bash
cd website        # or: cd restaurant
pnpm install
pnpm run dev
```

---

## Production

The production stack is described in [`compose.prod.yml`](./compose.prod.yml).

### Backend

Base PHP config served from `/var/www/cms`.

### Front

SvelteKit build served from `/var/www/website`.

List `pm2` processes:

```bash
pm2 list
```

There should be a single process serving the website front-end.

Restart the process after a build:

```bash
npm run build
pm2 restart 0
```

Start the process if it isn't running yet:

```bash
npm run build
pm2 start build/index.js
```
