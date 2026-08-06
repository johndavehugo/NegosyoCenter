# AGENTS.md

Plain PHP 8+ app (no Composer, no npm, no framework, no build step) served by XAMPP Apache + MySQL. UI is AdminLTE 3 (jQuery + Bootstrap 4); assets are vendored in `plugins/`, `dist/`, `build/`. README.md is a stub — ignore it.

## Setup / run
- Repo lives in `C:\xampp\htdocs\NegosyoCenter`; serve at `http://localhost/NegosyoCenter/` with Apache + MySQL running.
- Create `.env` from `.env.example` (gitignored). `config/dotenv.php` defines `loadEnv()` (requires PHP 8+ for `str_starts_with`); `config/db_connect.php` opens PDO handle `$con` (default fetch ASSOC, real prepares). Local dev uses DB `negosyo_center_db`, user `root`, no password.
- Import `negosyo_center_db.sql` into the DB (5 tables: `addresses`, `calamities`, `calamity_incidents`, `employers`, `juridicals`). No migration tooling — schema changes go back into that dump.
- No tests, CI, linters, or formatters exist. Verify changes in a browser; DB behavior is only testable with MySQL running.

## Architecture
- `index.php` — login screen; the Login button just redirects to `pages/msme/msme.php`. There is no real authentication.
- `server-side/*.php` — de-facto backend API: single file per feature, action-dispatch via `$_POST/$_GET['action']`, JSON responses (`business-handler.php`, `calamity-handler.php`, `price-handler.php`, `address-api.php`).
- `api/routes.php` — newer REST-style layer dispatching on `?resource=` or first path segment to controllers in `api/controllers/` (MSME, PriceMonitoring, Calamity). Both layers hit the same MySQL DB; new features can go in either, but match the existing layer.
- `pages/<feature>/` — feature pages (AdminLTE markup) including shared `../../pages/sidebar/sidebar.php` and per-page modals at the bottom (`modal-add.php`, `modal-view.php`, `modal-update.php`, ...).
- `scripts/<feature>/` — per-feature JS (jQuery, DataTables, SweetAlert2), split by concern (`*-table.js`, `*-add.js`, `*-update.js`, `*-renew.js`, ...).
- `global/` — shared PHP data, e.g. `industries.php` (industry letter codes A–Q).

## Gotchas
- `session.php` has hardcoded credentials for two remote MySQL DBs (scims at 192.168.3.5, sccdrrmo at 34.92.117.58) and is not included by any current page. Never modify or commit those credentials; `print_document.php` also references now-missing configs (`config/sp_franchise_db_config.php`, `config/sccdrrmo_db_config.php`) and is legacy/broken.
- Business data falls back to the external SCIMS API `https://vamosmobile.app/api/testjuridical/business` (`server-side/business-handler.php`, `api/controllers/MSMEController.php`); it fails silently offline, so don't assume DB-only behavior there.
- `server-side/address-api.php` proxies the PSGC API (`https://psgc.gitlab.io/api`) with a 24h JSON cache in `cache/`; only `cache/regions.json` is committed (`.gitignore` keeps the rest out).
- Page asset paths are relative from each subdirectory: `../../dist/...`, `../../plugins/...`.
- Odd files to not mistake for the real ones: `index mainte.php` (spaced filename, maintenance copy of login) and `pages/price-monitoring/other-agencies` (extensionless).
- CODEOWNERS: everything is owned by @johndavehugo.
