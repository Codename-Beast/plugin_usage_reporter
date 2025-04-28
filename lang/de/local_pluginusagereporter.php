<?php
// This file is part of local_pluginusagereporter – Moodle plugin for plugin usage reporting.
// Licensed under the GPL v3 or later – see https://www.gnu.org/copyleft/gpl.html

defined('MOODLE_INTERNAL') || die();

// Allgemein
$string['pluginname'] = 'Plugin Usage Reporter';
$string['privacy:metadata'] = 'Das Plugin speichert keine personenbezogenen Daten.';

// Dashboard / Übersicht
$string['dashboardtitle'] = 'Plugin Usage Dashboard';
$string['action_details'] = 'Details anzeigen';
$string['report_entries'] = 'Einträge';
$string['id'] = 'ID';
$string['date'] = 'Datum';
$string['timeframe'] = 'Zeitraum';
$string['entries'] = 'Einträge';
$string['actions'] = 'Aktionen';
$string['user'] = 'Benutzer';
$string['createdby'] = 'Erstellt von';
$string['createdat'] = 'Erstellt am';
$string['deleted_success'] = 'Bericht wurde erfolgreich gelöscht.';
$string['delete_failed'] = 'Löschen des Berichts fehlgeschlagen.';
$string['no_reports_found'] = 'Keine gespeicherten Berichte gefunden.';

// Manueller API-Trigger
$string['manualtrigger'] = 'Bericht manuell generieren und senden';
$string['manualtrigger_desc'] = 'Hier klicken, um einen Plugin-Nutzungsbericht manuell zu erstellen und zu senden.';

// Benachrichtigungen
$string['enable_notifications'] = 'E-Mail-Benachrichtigungen aktivieren';
$string['enable_notifications_desc'] = 'E-Mail-Benachrichtigungen für Ereignisse und API-Auslöser versenden.';
$string['notification_email'] = 'E-Mail-Adresse für Benachrichtigungen';

// Caching
$string['enable_caching'] = 'Caching aktivieren';
$string['enable_caching_desc'] = 'Caching für Plugin-Berichte aktivieren oder deaktivieren.';
$string['cache_ttl'] = 'Cache Lebensdauer (Sekunden)';
$string['cache_ttl_desc'] = 'Gültigkeitsdauer für Cache-Einträge in Sekunden.';

// Event API Trigger
$string['enable_event_api'] = 'Event-basierter API-Auslöser aktivieren';
$string['enable_event_api_desc'] = 'Berichte automatisch bei bestimmten Moodle-Ereignissen auslösen.';

// Aufgaben
$string['enable_scheduled_task'] = 'Geplante Aufgaben aktivieren';
$string['retry_delay'] = 'Wiederholungsverzögerung (Sekunden)';
$string['autodelete_days'] = 'Berichte nach (Tagen) automatisch löschen';

// Sicherheit
$string['ip_whitelist'] = 'IP-Whitelist (erlaubte IPs)';
$string['admin_override'] = 'Admin-Override zulassen (IP-Restriktionen umgehen)';

// API Einstellungen
$string['rate_limit'] = 'API-Rate-Limit (Anfragen)';
$string['rate_limit_window'] = 'API-Rate-Limit-Zeitfenster (Sekunden)';

// Datensammlung
$string['enablelogging'] = 'Event-Logging aktivieren';
$string['autodelete'] = 'Automatisches Löschen von gespeicherten Berichten nach (Tagen)';
$string['cli_logging'] = 'CLI-Logging für Systemaktionen aktivieren';

// Allgemeine Einstellungen
$string['defaulttimeframe'] = 'Standard-Zeitraum (Tage)';
$string['includehidden'] = 'Versteckte Kurse einbeziehen';

// Tabs
$string['generalsettings'] = 'Allgemeine Einstellungen';
$string['cachingsettings'] = 'Caching-Einstellungen';
$string['cachingsettings_desc'] = 'Konfiguriere das Verhalten für Berichtscaching';
$string['securitysettings'] = 'Sicherheitseinstellungen';
$string['securitysettings_desc'] = 'Konfiguration für Zugriff und Sicherheit';
$string['datacollectionsettings'] = 'Einstellungen zur Datensammlung';
$string['tasksettings'] = 'Automatisierungs- und Aufgaben-Einstellungen';
$string['apisettings'] = 'API-Einstellungen';
$string['notificationsettings'] = 'Benachrichtigungseinstellungen';

// Fehlermeldungen
$string['error_invalidtimeframe'] = 'Ungültiger Zeitraum angegeben.';
$string['error_db'] = 'Ein Datenbankfehler ist aufgetreten.';
$string['error_db_unsupported'] = 'Der Datenbanktyp wird für diese Funktion nicht unterstützt.';
$string['error_db_requires_mysql8'] = 'Diese Funktion erfordert MySQL 8.0 oder neuer.';
$string['error_invalidformat'] = 'Ungültiges Exportformat angegeben.';

// CLI- und Admin-Fehler
$string['adminusernotfound'] = 'Admin-Benutzer konnte nicht gefunden werden.';
$string['notallowed'] = 'Sie haben keine Berechtigung, diese Funktion zu verwenden.';
$string['clierror'] = 'CLI-Fehler: Sie müssen als Administrator angemeldet sein, um dieses Skript auszuführen.';
