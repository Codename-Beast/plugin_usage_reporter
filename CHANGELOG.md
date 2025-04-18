# Changelog
## Planning for v2.2

- [x] Unit testing / PHPUnit integration
- [ ] API rate limiting configurable
- [x] Webservice implemention (Work In Progress)
- [ ] Exponential backoff for retry mechanism
- [ ] Automated report sending via cron


Alle Änderungen im Plugin Usage Reporter werden hier dokumentiert.
## v2.1.1 — 2025-04-16
 - ✅ CSV / XML export via dashboard
 - [-] Multi-instance removed (not posible in Moodle)
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

## v2.0

- ✅ Major refactor for multi-instance support preparation
- ✅ Interface-based data fetcher (RawDataFetcher.php)
- ✅ Integration of central ErrorHandler
- ✅ Initial dashboard setup
- ✅ Settings structure created
- ✅ Logging framework base added

## v1.0

- ✅ Initial plugin setup
- ✅ Basic data fetching implemented
- ✅ Plugin settings created
- ✅ Base structure for Moodle 4.5+
- ✅ Admin interface (plugin registration)

---

> Updated 16.04.2024 22:37
> Dokumentiert am 10.02.2025
> Maintainer: Bernd Schreistetter
