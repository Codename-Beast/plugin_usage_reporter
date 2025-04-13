# Plugin Usage Reporter for Moodle

**Version:** 2.1  
**Date:** 2025-04-13  
**Author:** Bernd Schreistetter  
**License:** MIT  
**Compatibility:** Moodle 4.5+

---

## 📌 Project Overview

The Plugin Usage Reporter provides detailed reports on the usage of Moodle plugins (activities and resources) within visible courses.  
It analyzes all installed modules in courses that have been accessed within a defined period.

### Key Features:
- 🔍 Analyze plugin usage across all active courses
- 📊 Output available as HTML or text reports
- 🧩 Flexible timeframe (can be disabled)
- 📦 Fully Moodle 4.5+ compatible
- 📈 Role-based reporting: student / teacher / others
- 🚀 Multi-instance support (prepared)
- 🧩 Pagination for large datasets
- 💾 Caching using Moodle Cache API
- 🛠️ Centralized error handling via `ErrorHandler` class
- 🪵 Moodle Logging API integration for debugging
- 🧩 Interface-compliant structure for future fetchers
- 📄 Full versioning and structured documentation

---

## ✅ Current Project Status (As of 2025-04-13)

| Feature | Status |
|----------|---------|
| Plugin Usage Data Fetching | ✅ Completed |
| Pagination (limit/offset) | ✅ Completed |
| Optional timeframe (enable/disable) | ✅ Completed |
| Multi-instance preparation | ✅ Completed |
| Central ErrorHandler | ✅ Completed |
| Moodle Logging API | ✅ Completed |
| Data caching (Moodle Cache API) | ✅ Completed |
| Data transformation (JSON, Text) | ✅ Completed |
| Data validation | ✅ Completed |
| DataFetchInterface v1.2.0-10 | ✅ Completed |
| Unit tests | ⏳ Planned |
| Dashboard Pagination & Filter | ⏳ Planned |
| External API endpoint | ⏳ Planned |
| Multi-instance with DB connection | ⏳ Planned |

---

## 🆕 Latest Changes

### v2.1 (2025-04-13)
- ✅ RawDataFetcher: Full Interface compliance (fetch, cache, filter, transform, validate)
- ✅ Central ErrorHandler integration
- ✅ Moodle Cache API implemented
- ✅ Moodle Logging API integrated (Developer Mode)
- ✅ Pagination limit / offset support
- ✅ Multi-instance support prepared
- ✅ Role-based reporting (student / teacher / other)

### Versioning format:
Every PHP file now includes versioning in the header following this schema:
v1.1.1-10 A [13.04.2025] [Updates or Removal]
## 🚀 Installation

1. Copy the plugin folder to:  
   `local/pluginusagereporter/`

2. Ensure all required classes are present:
   - `RawDataFetcher.php`
   - `DataFetchInterface.php`
   - `ErrorHandler.php`

3. Go to Moodle admin panel → "Check for available updates".

4. Install the plugin.

---

## 🔮 Roadmap

| Feature | Status | Priority |
|---------|---------|-----------|
| Multi-instance finalization (connection manager) | ⏳ | High |
| REST API handler for external requests | ⏳ | High |
| Dashboard with pagination & filters | ⏳ | Medium |
| Optional caching disable switch | ⏳ | Low |
| Advanced output formats (CSV, XML export) | ⏳ | Medium |
| Admin email notifications on errors | ⏳ | Medium |
| Full unit testing / PHPUnit integration | ⏳ | High |
| Automated report sending via cron | ⏳ | Medium |

---

## 🛠 Configuration

Configuration is currently handled via plugin settings and method parameters:
- Timeframe: via `fetch_data(int $timeframe)`
- Pagination: via `setPagination(int $limit, int $offset)`
- Instance selection: via `setInstance(string $instance)`
- Caching: automatic through Moodle Cache API

---

## 🤝 Contributors & Special Thanks

- **Codename-Beast aka Ben** — Lead Developer & Architect
- Special thanks to: Benjamin (Eledia), Christopher(Eledia)

---