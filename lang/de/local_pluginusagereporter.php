<?php
/**
 * Deutsche Sprachdatei für Plugin Usage Reporter
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

defined('MOODLE_INTERNAL') || die();

// Plugin und Aufgabenname
$string['pluginname'] = 'Plugin Usage Reporter';
$string['taskname'] = 'Geplante Aufgabe: Plugin-Nutzungsbericht erzeugen';

// Einstellungen
$string['pluginsettings'] = 'Plugin-Einstellungen';
$string['emailsetting'] = 'E-Mail-Adresse';
$string['emailsetting_desc'] = 'E-Mail-Adresse, an die der Nutzungsbericht gesendet wird';
$string['timeframesetting'] = 'Zeitraum (Tage)';
$string['timeframesetting_desc'] = 'Anzahl der Tage in der Vergangenheit, für die Nutzungsdaten gesammelt werden sollen';
$string['frequencysetting'] = 'Berichtshäufigkeit';
$string['frequencysetting_desc'] = 'Wie häufig der Nutzungsbericht generiert werden soll';
$string['configdaterange'] = 'Datumsbereich für Bericht';
$string['configdaterange_desc'] = 'Zeitraum auswählen: 3, 6 oder 12 Monate';
$string['reporttimeframe'] = 'Berichtszeitraum';
$string['reporttimeframe_desc'] = 'Anzahl der Tage für den Bericht (zwischen 1 und 365)';
$string['usematerializedview'] = 'Materialisierte Ansicht verwenden';
$string['usematerializedview_desc'] = 'Aktiviert die Verwendung einer materialisierten Ansicht für bessere Leistung';
$string['emailformat'] = 'Berichtsformat für E-Mail';
$string['emailformat_desc'] = 'Wähle das Format für den E-Mail-Bericht (HTML oder Text)';

$string['enable_external_api'] = 'Externe API aktivieren';
$string['enable_external_api_desc'] = 'Wenn aktiviert, werden die Plugin-Nutzungsdaten an ein externes System gesendet.';
$string['external_api_url'] = 'Externe API-Endpunkt URL';
$string['external_api_key'] = 'Externer API-Schlüssel';
$string['enable_scheduled_task'] = 'Geplante Aufgabe aktivieren';
$string['retry_attempts'] = 'Anzahl der Wiederholungen (bei Fehlern)';
$string['retry_attempts_desc'] = 'Anzahl der Versuche, wenn die API-Anfrage fehlschlägt.';
$string['retry_delay'] = 'Wartezeit zwischen Wiederholungen (Sekunden)';
$string['retry_delay_desc'] = 'Verzögerung zwischen den Wiederholungsversuchen in Sekunden.';
$string['enable_caching'] = 'Caching aktivieren';
$string['enable_caching_desc'] = 'Aktiviert oder deaktiviert das Caching der Plugin-Daten.';
$string['cache_ttl'] = 'Cache-Lebenszeit (Sekunden)';
$string['cache_ttl_desc'] = 'Lebensdauer des Caches in Sekunden.';
$string['enable_event_api'] = 'Event-basierte API-Auslösung aktivieren';
$string['enable_event_api_desc'] = 'Bericht automatisch durch Moodle-Events auslösen.';
$string['enable_notifications'] = 'E-Mail-Benachrichtigungen aktivieren';
$string['enable_notifications_desc'] = 'Bei Fehlern eine E-Mail-Benachrichtigung an den Administrator senden.';

// Berichtszeiträume
$string['month3'] = 'Letzte 3 Monate';
$string['month6'] = 'Letzte 6 Monate';
$string['month12'] = 'Letzte 12 Monate';

// Häufigkeitsoptionen
$string['daily'] = 'Täglich';
$string['weekly'] = 'Wöchentlich';
$string['monthly'] = 'Monatlich';

// Berichtsspalten
$string['modulename'] = 'Modulname';
$string['coursename'] = 'Kursname';
$string['courseid'] = 'Kurs-ID';
$string['usagecount'] = 'Nutzungsanzahl';
$string['timestamp'] = 'Zeitstempel';

// Status- und Fehlermeldungen
$string['invalidemail'] = 'Ungültige E-Mail-Adresse in den Plugin-Einstellungen. Es wird keine E-Mail gesendet.';
$string['validemail'] = 'Gültige E-Mail-Adresse';
$string['checkingusage'] = 'Prüfe Plugin-Nutzung in den ausgewählten Kursen';
$string['foundusage'] = 'Gefundene Plugin-Nutzungen';
$string['pluginusagereport'] = 'Plugin-Nutzungsbericht';
$string['reportgenerated'] = 'Bericht generiert';
$string['sendingreport'] = 'Bericht wird per E-Mail versendet';
$string['emailsent'] = 'Bericht erfolgreich per E-Mail versendet';
$string['materializedview_unsupported'] = 'Materialisierte Ansichten werden in {$a} nicht unterstützt';
$string['materializedview_missing'] = 'Materialisierte Ansicht nicht in der Datenbank gefunden';
$string['materializedview_error'] = 'Fehler bei der Abfrage der materialisierten Ansicht';
$string['fallback_raw_query'] = 'Wechsle zur Rohdaten-Abfrage';

// Cronjob-Meldungen
$string['cronjobmessage'] = 'Plugin Usage Reporter Cronjob läuft';
$string['cronjobnotfound'] = 'Geplante Aufgabe {$a} wurde nicht gefunden';
$string['cronjobregistered'] = 'Geplante Aufgabe {$a} wurde erfolgreich registriert';
$string['cronjobnotfoundexception'] = 'Die geplante Aufgabe konnte nicht registriert werden';
$string['adminusernotfound'] = 'Admin-Benutzer konnte nicht gefunden werden';

// Dashboard
$string['dashboardtitle'] = 'Plugin Usage Dashboard';
$string['action_details'] = 'Details anzeigen';
$string['report_entries'] = 'Einträge';
$string['id'] = 'ID';
$string['date'] = 'Datum';
$string['timeframe'] = 'Zeitrahmen';
$string['entries'] = 'Einträge';
$string['actions'] = 'Aktionen';
$string['manualtrigger'] = 'Bericht manuell erzeugen und senden';
$string['manualtrigger_desc'] = 'Hier klicken, um den Plugin-Bericht manuell zu erzeugen und zu senden.';
$string['reporthistory'] = 'Berichtshistorie';
$string['nologentries'] = 'Keine Protokolleinträge gefunden.';

