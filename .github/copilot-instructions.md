@../AGENTS.md

# Copilot-specific instructions

- The rules in `AGENTS.md` (repo root) are the source of truth. Add project rules there,
  not here — this file is only for Copilot specifics.
- Prefer the VS Code workspace tools (file search, grep search, read file) over shelling
  out to `find`/`grep`/`cat`.
- Terminal commands run in a persistent zsh session: quote globs (`--include='*.php'`)
  and prefer `&&` over `;` when chaining.
- Any command touching a project toolchain must go through
  `docker compose -f compose.dev.yml exec -T ...` (see `AGENTS.md`).
