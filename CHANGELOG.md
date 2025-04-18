# Changelog

## Planning for v2.2
- [x] Unit testing / PHPUnit integration
- [x] Webservice implemention (**finalized** ✅)
- [x] API refactor: Moodle Webservice only (no custom API)
- [x] API filtering: timeframe, plugin, limit, offset
- [x] ErrorHandler test
- [x] Report Task Test
- [x] REST API Test
- [x] Cache and Logging integration
- [ ] API rate limiting configurable
- [ ] Exponential backoff for retry mechanism
- [ ] Automated report sending via cron

Alle Änderungen im Plugin Usage Reporter werden hier dokumentiert.

---

## v2.2 — 2025-04-18
### ✅ Added
- Moodle Webservice `get_plugin_usage_data` (REST)
- Sicherheitsprüfung via `has_capability()` und `validate_context()`
- Parameter: `timeframe`, `pluginfilter`, `limit`, `offset`
- Neue Unit Tests:
  - `raw_data_fetcher_test`
  - `generate_report_task_test`
  - `report_api_service_test`
  - `error_handler_test`
- Event-Listener `PluginUsageEvent.php` → Report API trigger
- Grafana-kompatible Ausgabe via JSON
- Refactored caching (Moodle Cache API)

### 🔧 Changed
- Webservice nutzt vollständig PSR-4 Autoloading
- Logging und Fehlerbehandlung zentralisiert
- Reportstruktur modularisiert für HTML / CSV / Text / XML
- Retry-Konfiguration über Settings steuerbar
- Logging ersetzt direkte `mtrace()`-Ausgaben

### ❌ Removed
- Eigene API (`report_api.php`, `api_handler.php`) entfernt
- Instanzlogik: kein `setInstance()`, keine Loops über `instances[]`
- Keine dynamischen Cachekeys mehr mit Instanznamen

---

## v2.1.1 — 2025-04-16
- ✅ CSV / XML export via dashboard
- ❌ Multi-instance removed (not possible in Moodle)
- ✅ Refactor Code.
- ✅ Add Changes to CHANGELOG.md updated to reflect current state.

## v2.1 — 2025-04-13
- ✅ Event-based API trigger via course and module views
- ✅ Retry mechanism with configurable attempts and delay
- ✅ Dashboard: filter and pagination operational
- ✅ Caching: configurable TTL and enable/disable options active
- ✅ ErrorHandler non-static calls corrected (IDE compliance)
- ✅ Complete README.md and CHANGELOG.md updated to reflect current state
- ✅ Language files EN/DE completed

## v2.0 — 2025-03-01
- ✅ Major refactor for multi-instance support preparation
- ✅ Interface-based data fetcher (RawDataFetcher.php)
- ✅ Integration of central ErrorHandler
- ✅ Initial dashboard setup
- ✅ Settings structure created
- ✅ Logging framework base added

## v1.0 — 2024-12-01
- ✅ Initial plugin setup
- ✅ Basic data fetching implemented
- ✅ Plugin settings created
- ✅ Base structure for Moodle 4.5+
- ✅ Admin interface (plugin registration)

---
> Updated 18.04.2025 20:45
> Maintainer: Bernd Schreistetter
