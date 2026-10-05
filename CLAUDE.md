# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

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

# Comments

A comment must make sense to a developer who has never seen the prompt, task, conversation, or implementation history. If it wouldn't, don't write it.

Default to no comment. Add one only for information the code cannot clearly express: non-obvious rationale, invariants, assumptions, subtle edge cases, upstream/library workarounds, concurrency or lifecycle constraints, security or meaningful performance reasons, external protocol requirements, or genuinely non-obvious algorithms.

Never comment:

- what the code plainly does
- your reasoning, plan, or implementation process
- the change itself (`Updated to...`, `New approach...`, `Changed...`, `Fixed...`, `Removed...`)
- task narration (`Now we...`, `Next...`, `We need to...`, `Here we...`)

Git history records changes; source comments describe the code as it exists now.

Prefer expressive names over explanatory comments. Don't add comments or docstrings to code you didn't materially change. Match the comment style and density of the surrounding file.