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
