# Changelog

## Planned for v2.2
- [x] API rate limiting and usage counters
- [x] Extended course-based statistics via Webservice
- [x] **CLI support for admins** — Allow retrieval of usage data via command-line interface, enabling admins to access and export plugin usage reports directly from the CLI.
- [ ] Optional exponential backoff logic for retries
- [ ] Webservice token whitelist & optional API key mode
- [ ] Optional output formats: CSV endpoint for Webservice
- [ ] Automated report emailing (configurable via settings)
- [ ] Code coverage metrics for all core classes
- [ ] Grafana dashboard example JSON & documentation

All notable changes to the Plugin Usage Reporter plugin are documented here.

---
## v2.1.3 — 2025-04-21
### ✨ Enhancements
- **Input validation:** Strong type and range validation for `timeframe` (allowed: 1–3650 days) in RawDataFetcher, preventing invalid or dangerous values.
- **Cache key safety:** Cache keys sanitized using regular expressions to avoid access or corruption by faulty/injected characters.
- **SQL role aggregation:** User roles in usage reports are now DB-agnostic and truncated at the SQL level (max 255 chars), boosting compatibility and performance on large courses/platforms.
- **Error handling:** Extended transformData to robustly throw moodle_exception for unsupported formats, ensuring clear feedback in REST and internal API use.
- **Configurability:** Fully utilizes plugin settings for cache TTL, caching toggle, and dynamic limits; disables/returns early on empty or misconfigured caches.
- **Developer Experience:** 
  - Unified property names for cache keys and error handler.
  - Refactored constructor to use dependency injection, setting up internal cache and error handling.
  - Added/modernized inline documentation and comments for better code navigation.
- **Logging:** Consistent use of Moodle's debugging function for tracing, validation, and issue discovery.

### 🛠️ Fixes (since v2.1.2)
- Removed dead/unused query parameters and redundant checks in data fetching path.
- Harmonized exception messages and error code locations.
---

## v2.1.2 — 2025-04-18
### 🛠️ Fixes
- Corrected `riskbitmask` in `access.php` from `RISK_SPAM` to `RISK_CONFIG`
- Removed deprecated `local_pluginusagereporter_cron()` function (tasks only)
- Documented version timestamp in `version.php` for dev branch use
- `dashboard.php` now respects `enable_caching` setting before writing cache
- Removed obsolete JSON instance setting (multi-instance support deprecated)
- Removed unused `usematerializedview` setting and related lang strings
- Cleaned up language string set (cronjobmessage, etc.) and ensured completeness
- Deleted unused `external_api_key` setting (Webservice tokens now default)
- Improved error handling with:
  - Structured fallback logic
  - Sensitive data masking in error messages
  - Logging via internal `logger::add()` calls
---
## v2.1.1 — 2025-04-18
### ✅ Added
- Moodle-native Webservice `get_plugin_usage_data` (REST)
- Webservice security with `has_capability()` and `validate_context()`
- Optional Webservice filters: `timeframe`, `pluginfilter`, `limit`, `offset`
- New unit tests for full plugin coverage:
  - `raw_data_fetcher_test`
  - `generate_report_task_test`
  - `report_api_service_test`
  - `error_handler_test`
- Event-based API trigger via `PluginUsageEvent.php`
- Grafana-compatible JSON response for analytics integration
- Extended caching logic (Moodle Cache API) with TTL configuration

### 🔧 Changed
- Fully PSR-4 compliant autoloading and namespaces
- Logging centralized using `logger::add()` (replaced `mtrace()`)
- Error handling standardized via `ErrorHandler` class
- Report output now modular: HTML, CSV, XML, Text
- Retry mechanism for scheduled task is now configurable via plugin settings
- Code optimization:
  - Removed redundant checks
  - Simplified conditionals
  - Removed unnecessary dependency on `instance` logic

### ❌ Removed
- Custom REST API entrypoint (`report_api.php`)
- Deprecated `api_handler.php`
- Entire instance handling logic (`setInstance()`, `$instances[]`, etc.)
- Dynamic cache keys based on instance name

---

## v2.1 — 2025-04-13
- ✅ Event-based API trigger via course and module views
- ✅ Retry mechanism with configurable attempts and delay
- ✅ Dashboard: filter and pagination implemented
- ✅ Configurable caching via Moodle Cache API
- ✅ Refactored ErrorHandler for IDE and test compliance
- ✅ Completed test coverage for data processing components
- ✅ Language files (English/German) completed
---
## v2.0 — 2025-03-01
- ✅ Major codebase refactor
- ✅ Interface-based data layer (RawDataFetcher.php)
- ✅ Initial dashboard layout
- ✅ Modular settings and configuration
- ✅ Integrated logging layer
---
## v1.0 — 2024-12-01
- ✅ Initial plugin release
- ✅ Basic plugin usage statistics
- ✅ Initial scheduled task and admin integration
- ✅ Support for Moodle 4.5+

---
> Last updated: 21.04.2025 — Maintainer: Bernd Schreistetter
