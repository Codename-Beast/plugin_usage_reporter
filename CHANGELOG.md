# Changelog

## Planned for v2.2
- [ ] API rate limiting and usage counters
- [ ] Optional exponential backoff logic for retries
- [ ] Webservice token whitelist & optional API key mode
- [x] Grafana dashboard example JSON & documentation
- [ ] Optional output formats: CSV endpoint for Webservice
- [ ] Automated report emailing (configurable via settings)
- [ ] Code coverage metrics for all core classes
- [ ] Extended course-based statistics via Webservice

All notable changes to the Plugin Usage Reporter plugin are documented here.

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

## v2.0 — 2025-03-01
- ✅ Major codebase refactor
- ✅ Interface-based data layer (RawDataFetcher.php)
- ✅ Initial dashboard layout
- ✅ Modular settings and configuration
- ✅ Integrated logging layer

## v1.0 — 2024-12-01
- ✅ Initial plugin release
- ✅ Basic plugin usage statistics
- ✅ Initial scheduled task and admin integration
- ✅ Support for Moodle 4.5+

---
> Last updated: 18.04.2025 — Maintainer: Bernd Schreistetter