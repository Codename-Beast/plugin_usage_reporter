# Plugin Usage Reporter for Moodle

**Version:** 2.3.0  
**PTU (Planned Test Upgrade):** 2.4.0  

**Last Updated:** 28.04.2025  

**Author:** Bernd Schreistetter  
**Compatibility:** Moodle 4.5+ and 5.0

---

## 📊 Project Overview

The Plugin Usage Reporter provides a comprehensive and customizable overview of which Moodle plugins (activities, modules) are actively used across your site.  
It focuses on visibility, last access timeframe, and provides exportable data in various formats.

---

### 🔧 Features
- Plugin usage reporting across visible and accessed courses
- Customizable timeframe (days)
- Output formats: **HTML**, **Text**, **CSV**, **XML**, **JSON**
- Moodle **Cache API** supported (configurable TTL)
- Developer logging via Moodle **Logging API**
- Centralized error handling using `ErrorHandler`
- Scheduled report tasks with retry mechanism (configurable)
- Pagination + filtering in dashboard (Mustache + Renderer)
- Native **REST Webservice** with optional filters: `timeframe`, `pluginfilter`, `limit`, `offset`
- JSON structure compatible with **Grafana** (via JSON API plugin)
- Event-based report trigger (`PluginUsageEvent`)
- Multilingual support: **English (EN)** and **German (DE)**

---

## 🛠️ Technical Highlights (as of v2.3.0)

- **Strict input validation** for timeframe, limit, offset
- **Cache key sanitation** with regex
- **Cross-DB safe SQL** for user role aggregation
- **Unified error handling** and debug logging (Moodle Debugging API)
- **Configurable report caching** and auto-deletion after X days
- **Separation of concerns**: fetchers, generators, and transformers modularized
- **Ready for REST API integrations** with secure token-based access
- **Dashboard UI fully Mustache-based**

---

## 📜 Release Notes

**May 01, 2025 — 2.3.0:**  
This major update introduces a modular generator system, auto-deletion of reports, expanded webservice support, stricter validations, and a fully redesigned dashboard interface with export/download options.

---

## ✅ Current Project Status (as of May 2025)

| Feature                        | Status      |
|--------------------------------|-------------|
| Plugin Usage Data Fetching     | ✅ Improved |
| Report Output Formats          | ✅ Extended |
| Pagination (limit/offset)      | ✅ Hardened |
| Timeframe Filtering            | ✅ Validated |
| Retry Mechanism (Tasks)         | ✅ Hardened |
| Centralized ErrorHandler       | ✅ Unified |
| Moodle Logging API             | ✅ Used |
| Caching (Moodle API)           | ✅ Improved |
| Webservice (REST)              | ✅ Stable |
| Grafana JSON Output            | ✅ Ready |
| Dashboard Pagination           | ✅ Stable |
| Event-based Trigger            | ✅ Ready |
| Unit Tests                     | ⏳ Expanded (Full by v2.4.0) |
| API Rate Limiting              | ⏳ Planned (v2.4+) |
| CSV/Extended REST Output       | ⏳ Planned (v2.4+) |

---

## 🧪 Unit Test Coverage

| Component              | Status | Notes                                     |
|------------------------|--------|-------------------------------------------|
| `RawDataFetcher`       | ✅ | Fetching, Caching, Filtering, Validating |
| `generate_report_task` | ✅ | Executes, Logs, Error-safe |
| `report_api_service`   | ✅ | REST structure & filter logic |
| `ErrorHandler`         | ✅ | Exception logging validation |
| Dashboard UI Tests     | ⏳ | Planned for v2.4.0 |
| Event Trigger Tests    | ⏳ | Planned full chain for v2.4.0 |
| Report Output Validation | ⏳ | Planned for v2.4.0 |

**Important:**  
⚡ Unit and functional tests are partially expanded and will be fully completed and validated with **v2.4.0**.

---

## 🔐 Webservice Usage


### Endpoint
/webservice/rest/server.php


### Required Parameters
| Param                | Example                                           |
|----------------------|---------------------------------------------------|
| `wstoken`            |  Moodle Token                                     |
| `wsfunction`         | `local_pluginusagereporter_get_plugin_usage_data` |
| `moodlewsrestformat` | `json`                                            |

### Optional Parameters
| Param          | Type   | Example    |
|----------------|--------|------------|
| `timeframe`    | int    | 90         |
| `pluginfilter` | string | `mod_quiz` |
| `limit`        | int    | 20         |
| `offset`       | int    | 0          |

---

## License

GNU General Public License v3.0 or later.

---

## Author

**Bernd Schreistetter**  
Project Lead & Developer

---



