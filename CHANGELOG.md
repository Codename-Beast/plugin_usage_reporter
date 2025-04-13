# Changelog

Alle Änderungen im Plugin Usage Reporter werden hier dokumentiert.

## v2.1 — 2025-04-13

- ✅ Multi-instance processing fully active (task & API)
- ✅ Pro-instance logging (records processed per instance)
- ✅ Event-based API trigger via course and module views
- ✅ Retry mechanism with configurable attempts and delay
- ✅ Dashboard: filter and pagination operational
- ✅ Caching: configurable TTL and enable/disable options active
- ✅ ErrorHandler non-static calls corrected (IDE compliance)
- ✅ Complete README.md and CHANGELOG.md updated to reflect current state
- ✅ Language files EN/DE completed
- ✅ Project production-ready, clean Savepoint

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

## Planning for v2.2

- [x] CSV / XML export via dashboard (Work in Progress)
- [ ] Unit testing / PHPUnit integration
- [ ] API rate limiting configurable
- [ ] Per-instance API keys
- [ ] Exponential backoff for retry mechanism
- [ ] Automated report sending via cron

> Dokumentiert am 13.04.2025
> Maintainer: Bernd Schreistetter
