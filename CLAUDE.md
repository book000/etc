# CLAUDE.md

## Purpose

`etc` is a personal "junk drawer" archive of miscellaneous, standalone code snippets and utilities that the owner throws in for later reuse (see `README.md`). It is **not** a single application: each file is an independent script or class, most dating from 2015-2019, spanning several unrelated topics (Discord, Minecraft, Steam, train timetables, Android backups, Nginx log parsing, etc.).

Treat every file as its own tiny project. There is no shared framework tying them together.

## Repository structure

- Files live flat in the repository root; there is no `src/` layout.
- File languages (by count): PHP (`*.php`, `*.class.php`), Java (`*.java`), HTML, one Batch (`.bat`) and one shell (`.sh`) script, plus data files (`Materials_1.12.2.txt`, `timetable.json`).
- `README.md` is the index: it documents most files with their initial-commit date, purpose, and usage. When you add or meaningfully change a file, update `README.md` to match — it is the only map of this repo.
- The `gh-pages` branch (plus GitHub Pages enabled on this repo) serves the HTML files, e.g. `gps.html` and `denchi.html`, at `https://book000.github.io/etc/`. Editing those HTML files affects the published page.

## Build / test / lint

There is **none**. This repository has no package manager, no build system, no test suite, no linter, and no CI beyond GitHub Pages publishing. Do not invent or run `npm`, `composer`, `mvn`, etc. — no manifest files exist.

Verify changes manually and per-file:
- PHP files run under a PHP CLI/server; most expect specific runtime context (credentials, external services, a Minecraft/Discord/Steam environment) and cannot be fully exercised standalone.
- Java files (`ParseSelector.java`, `DiscordEmbed.java`, `replaceMaterialToGiveName.java`) are extracted snippets, not a buildable Java project — they reference external classes/libraries not present here.
- HTML files can be opened directly in a browser.

## Coding conventions

- Match the existing style of the specific file you are editing; there is no repo-wide standard because files come from many different eras and languages.
- Existing comments and README prose are in Japanese — keep that language when editing existing files.
- Do **not** mass-reformat or "modernize" old files. Several are intentionally preserved as historical artifacts (the README openly notes some are messy). Make the smallest change that satisfies the request.

## Security

- Never commit secrets (API tokens, Discord bot tokens, passwords, cookies). The README notes a plaintext password was committed in this repo's history in the past (for a server that no longer exists) — do not repeat that mistake.
- Several files reference now-outdated endpoints/APIs (e.g. `discordapp.com`, Minecraft 1.12.2 IDs). Do not assume they still work; flag staleness rather than silently "fixing" archived code unless asked.

## Documentation update rules

- When adding a new file, add a matching entry to `README.md` (keep its existing style: `## <filename>`, initial date, short description).
- Update this `CLAUDE.md` only if the repository's fundamental nature changes (e.g. it gains a real build system or CI) — routine file additions do not require CLAUDE.md changes.
