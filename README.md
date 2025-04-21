# Plugin Usage Reporter for Moodle

**Version:** 2.1.2  
**PTU:** 2.1.3

**Last Updated:** 21.04.2025  

**Author:** Bernd Schreistetter  
**Compatibility:** Moodle 4.5+ and 5.0

---

## 📊 Project Overview

The Plugin Usage Reporter provides a comprehensive and customizable overview of which Moodle plugins (activities, modules) are actively used across your site. It focuses on visibility, last access timeframe, and provides exportable data.

### 🔧 Features
- Plugin usage reporting across visible and accessed courses
- Customizable timeframe (days)
- Output formats: **HTML**, **Text**, **CSV**, **XML**
- Moodle **Cache API** supported (configurable TTL)
- Developer logging via Moodle **Logging API**
- **Centralized error handling** using `ErrorHandler`
- Scheduled report task with retry mechanism (configurable)
- Pagination + filtering in dashboard (Mustache + Renderer)
- Native **REST Webservice** with optional filters: `timeframe`, `pluginfilter`, `limit`, `offset`
- JSON structure compatible with **Grafana** (via JSON API plugin)
- Event-based report trigger (`PluginUsageEvent`)
- Multilingual support (EN / DE)

## Status as of April 21, 2025
 **Stricter input validation** on timeframes and critical parameters, guarding against bad data and exploits.
- **Enhanced cache key handling**: Regex sanitation ensures stable and safe caching.
- **Cross-DB robust SQL** for user role aggregation (roles truncated at 255 chars for huge courses/sites).
- **Consistent and detailed developer logging** (Moodle Debug API everywhere).
- **Error handling improvements**: transformData now throws clear, actionable exceptions for unsupported formats.
- **Centralized settings usage:** Dynamically respects plugin configuration for cache TTL, enable/disable, and resource limits.
- **Improved inline documentation and maintainability** across major classes and interfaces.
- ...plus all prior features: REST API, multiple output formats (HTML, Text, CSV, XML), configurable caching & pagination, filtering, Grafana compatibility, and multi-language support.

---
## Release Notes

**April 21, 2025 — 2.1.3:**  
This update brings robust validation, hardened caching, more reliable SQL role handling, and enhanced error reporting, ensuring security, compatibility, and maintainability. Focus remains on extensible and safe data reporting for enterprise Moodle environments.
---

## ✅ Current Project Status (2025-04-21)

| Feature                        | Status      |
|--------------------------------|-------------|
| Plugin Usage Data Fetching     | ✅ Improved |
| Report Output Formats          | ✅ Robust   |
| Pagination (limit/offset)      | ✅ Stable   |
| Timeframe Filtering            | ✅ Hardened |
| Retry Mechanism (Tasks)        | ✅ Stable  |
| Central ErrorHandler           | ✅ Refined  |
| Moodle Logging API             | ✅ Unified  |
| Caching (Moodle API)           | ✅ Improved |
| Webservice (REST)              | ✅ Stable   |
| Grafana JSON Output            | ✅ Ready    |
| Dashboard Pagination           | ✅ Stable   |
| Event-based Trigger            | ✅ Ready    |
| Unit Tests                     | ✅ Expanded |
| API Rate Limiting              | ⏳ Planned  |
| CSV REST Output                | ⏳ Planned  |

---

## 🧪 Unit Test Coverage

| Component              | Status | Notes                                     |
|------------------------|--------|-------------------------------------------|
| `RawDataFetcher`       |   ✅   | Fetching, Caching, Filtering, Validating |
| `generate_report_task` |   ✅   | Executes, logs, error-safe               |
| `report_api_service`   |   ✅   | REST structure & filter logic            |
| `ErrorHandler`         |   ✅   | Exception logging validation             |
| Dashboard (Pagination/Filter) | ⏳ | Planned UI test                        |
| Event Trigger          | ⏳ | Full event chain test planned                 |
| Report Format (HTML/Text) | ⏳ | Output structure validation                |
| API Rate Limiting | ⏳ | Planned for v2.2 |

---

## 🔐 Webservice Usage

### Endpoint
/webservice/rest/server.php

### Required params
| Param                | Example                                           |
|----------------------|---------------------------------------------------|
| `wstoken`            |  Moodle Token                                     |
| `wsfunction`         | `local_pluginusagereporter_get_plugin_usage_data` |
| `moodlewsrestformat` | `json`                                            |

### Optional
| Param          | Type   | Example    |
|----------------|--------|------------|
| `timeframe`    | int    | 90         |
| `pluginfilter` | string | `mod_quiz` |
| `limit`        | int    | 20         |
| `offset`       | int    | 0          |


