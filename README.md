# Plugin Usage Reporter

## Project Description

The **Plugin Usage Reporter** is a powerful Moodle plugin designed to monitor and analyze the usage of plugins in visible courses. It provides administrators and educators with valuable insights into user interactions with various plugins. By collecting and analyzing usage data over a period of up to 365 days, the plugin enables informed decision-making regarding the effectiveness and necessity of the deployed plugins. Reports are generated at regular intervals and can be sent in both HTML and plaintext formats to a predefined email address for easy distribution and archiving. This plugin utilizes the Moodle Task API to schedule and execute report generation.

## Features

- **Usage Reports**: Tracks the usage of plugins/mods in visible courses over the past 365 days.
- **Email Reporting**: Generates and sends HTML and plaintext reports to a configurable email address.
- **Dashboard**: Provides an interactive dashboard for visualizing usage statistics.
- **Cronjob**: Implemented as a scheduled cron job that runs daily.
- **Security Measures**: SQL queries are secured to prevent SQL injection. HTML outputs are safely escaped.
- **Moodle Task API**: Utilizes the API for scheduling and executing report generation.

## Requirements

- Moodle 4.5 or higher
- PHP 8 or higher
- The server must be configured for sending emails.

## Installation

### 1. Install the Plugin

1. Upload the `plugin_usage_reporter` folder into the `moodle/local/` directory of your Moodle system.

   Example: moodle/ ├── local/ │ └── plugin_usage_reporter/
2. Go to **Site Administration** > **Plugins** > **Manage Plugins** and install the plugin.

### 2. Email Configuration

Ensure that your Moodle instance is properly configured for sending emails. This can be configured under **Site Administration** > **Server** > **Email**.

### 3. Plugin Configuration

After installation, you can find and configure the plugin under **Site Administration** > **Plugins** > **Local Plugins**. Configure the email address where the report will be sent and the frequency of report generation (e.g., daily, weekly).

## Usage

### Task Execution

The plugin uses the Moodle Task API to generate reports. You can manually execute the task or set up a schedule for automatic execution. The task checks the usage of plugins in visible courses from the past year and generates an HTML report.