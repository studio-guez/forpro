@AGENTS.md

# Claude-specific instructions

- The rules in `AGENTS.md` are the source of truth. Add project rules there, not here —
  this file is only for Claude Code specifics.
- Use Plan mode before large refactors (multi-file changes, `Utils.php` extractions,
  anything touching the JSON contract consumed by the frontends).
- Do not commit or push unless explicitly asked. Never use `--no-verify`.
- Prefer `Grep`/`Glob`/`Read` over shelling out to `grep`/`find`/`cat`.
- Every `Bash` call that touches a project toolchain must go through
  `docker compose -f compose.dev.yml exec -T ...` (see `AGENTS.md`). If a container is
  down, start it instead of falling back to the host.
- zsh gotcha: unmatched globs error out, so quote them (`--include='*.php'`), and prefer
  `&&` over `;` when chaining.
