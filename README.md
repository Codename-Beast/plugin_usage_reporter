# Plugin Usage Reporter for Moodle

**Version:** 2.1  
**Date:** 2025-04-13  
**Author:** Bernd Schreistetter  
**Compatibility:** Moodle 4.5+

---

##  Project Overview

The Plugin Usage Reporter provides detailed reports on the usage of Moodle plugins (activities and resources) within visible courses.  
It analyzes all installed modules in courses that have been accessed within a defined period.

### Key Features:
- 🔍 Analyze plugin usage across all active courses
- 📊 Output available as HTML, text, CSV, or XML reports
- 🧩 Flexible timeframe (configurable or disabled)
- 📦 Fully Moodle 4.5+ compatible
- 📈 Role-based reporting: student / teacher / others
- 🚀 Multi-instance support (✅ active)
- 🧩 Pagination for large datasets (✅ active)
- 💾 Caching using Moodle Cache API (configurable)
- 🛠️ Centralized error handling via `ErrorHandler` class
- 🪵 Moodle Logging API integration for debugging
- 🔒 REST API for external systems (Grafana, etc.)
- 🔔 Optional: Admin email notifications on errors
- 🔥 Event-based API triggers (Live Reporting!)
- 📊 Pro-instance monitoring (records processed per instance)
- 📄 Full versioning and structured documentation

---

## ✅ Current Project Status (As of 2025-04-13)

| Feature | Status |
|----------|---------|
| Plugin Usage Data Fetching | ✅ Completed |
| Pagination (limit/offset) | ✅ Completed |
| Optional timeframe (enable/disable) | ✅ Completed |
| Multi-instance processing | ✅ Completed |
| Central ErrorHandler | ✅ Completed |
| Moodle Logging API | ✅ Completed |
| Data caching (Moodle Cache API) | ✅ Completed |
| Data transformation (JSON, Text, CSV, XML) | ✅ Completed |
| Data validation | ✅ Completed |
| Dashboard Pagination & Filter | ✅ Completed |
| External API endpoint | ✅ Completed |
| Event-based trigger | ✅ Completed |
| Multi-instance with DB connection | ✅ Completed |
| Retry mechanism with delay (configurable) | ✅ Completed |
| Pro-Instance data count logging | ✅ Completed |
| Unit tests | ⏳ Planned (ErrorHandler tests ready) |

---

## 🆕 Latest Changes

### v2.1 (2025-04-13)
- ✅ RawDataFetcher: Full Interface compliance (fetch, cache, filter, transform, validate)
- ✅ Central ErrorHandler integration
- ✅ Moodle Cache API implemented (configurable)
- ✅ Moodle Logging API integrated (Developer Mode)
- ✅ Pagination limit / offset support
- ✅ Multi-instance processing active (task & API)
- ✅ Role-based reporting (student / teacher / other)
- ✅ REST API with authentication and rate limiting
- ✅ Event-based trigger for real-time reporting
- ✅ Retry mechanism (attempts & delay configurable)
- ✅ Pro-Instance logging of processed records

### Versioning format:
Every PHP file now includes versioning in the header following this schema:
`v1.1.1-10 A [13.04.2025] [Updates or Removal]`

> 🔖 Current internal versioning: **v1.1.1-10 K**

---

##  Installation

1. Copy the plugin folder to:  
   `local/pluginusagereporter/`

2. Install the plugin via Moodle admin panel or CLI:
   ```bash
   php admin/cli/upgrade.php


# Examples  for Multi-Instance Config:
```json
{
  "moodle_instance_1": {
    "dbhost": "localhost",
    "dbname": "moodle1",
    "dbuser": "user",
    "dbpass": "password"
  },
  "moodle_instance_2": {
    "dbhost": "localhost",
    "dbname": "moodle2",
    "dbuser": "user",
    "dbpass": "password"
  }
}