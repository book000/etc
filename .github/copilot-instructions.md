# Copilot code review instructions

This repository (`etc`) is a personal archive of unrelated, standalone snippets and utilities — not a single application. Each file is its own tiny project (PHP, Java, HTML, Batch, shell), most from 2015-2019. Review each file on its own terms; there is no shared framework, build system, test suite, or linter.

## What to prioritize in review

- **Secrets**: flag any hardcoded credentials — API tokens, Discord bot tokens, passwords, session cookies, private URLs. This is the single most important check; the repo has a history of an accidentally committed plaintext password.
- **Input handling in PHP**: for scripts that build shell commands, SQL, or file paths from external input, flag command/SQL injection and path traversal risks.
- **`README.md` sync**: `README.md` is the index of this repo. If a PR adds a new file without a corresponding README entry (or removes/renames a file the README still describes), flag the mismatch.
- **GitHub Pages files**: `gps.html`, `denchi.html`, and other HTML served via the `gh-pages` branch are user-facing. Flag broken markup or hardcoded secrets in these.

## What NOT to flag

- **Do not** demand tests, CI, a build config, linter setup, or dependency manifests — this repo intentionally has none.
- **Do not** ask to "modernize" or reformat old code. Many files are deliberately preserved as historical artifacts (the README notes some are messy). Only comment on the lines a PR actually changes.
- **Do not** flag Japanese comments/prose or mixed coding styles across files — that is expected here, since files come from different eras and languages.
- **Do not** flag references to outdated third-party APIs (e.g. `discordapp.com`, Minecraft 1.12.2 IDs) as bugs in archived files unless the PR is specifically updating them.

Keep review comments concise and specific to the changed lines.
