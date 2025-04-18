# Plugin Usage Reporter for Moodle

**Version:** 2.1.2  
**Last Updated:** 18.04.2025  
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

---

## ✅ Current Project Status

| Feature | Status |
|----------------------------|----------------------------|
| Plugin Usage Data Fetching | ✅ Completed              |
| Report Output Formats      | ✅ HTML, Text, CSV, XML   |
| Pagination (limit/offset)  | ✅ Completed              |
| Timeframe Filtering        | ✅ Configurable           |
| Retry Mechanism (Tasks)    | ✅ With delay & attempts  |
| Central ErrorHandler       | ✅ Integrated             |
| Moodle Logging API         | ✅ Active                 |
| Caching (Moodle API)       | ✅ Enabled                |
| Webservice (REST)          | ✅ Fully implemented      |
| Grafana JSON Output        | ✅ Compatible             |
| Dashboard Pagination       | ✅ Completed              |
| Event-based Trigger        | ✅ Implemented            |
| Unit Tests                 | ✅ Core tested            |
| API Rate Limiting          | ⏳ Planned v2.2           |
| API Key Security           | ❌ Removed                |
| CSV REST Output            | ⏳ Optional v2.2          |

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


