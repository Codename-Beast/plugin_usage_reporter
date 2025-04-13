<?php
/**
 * This plugin provides reports on plugin usage for Moodle .
 *
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT 
 */

// German language strings for local_pluginusagereporter
defined('MOODLE_INTERNAL') || die();
// General strings for the plugin
$string['pluginname'] = 'Plugin Nutzungsreporter';
$string['taskname'] = 'Plugin Nutzungsbericht generieren';
$string['invalidemail'] = 'Ungültige E-Mail-Adresse in den Plugin-Einstellungen. Keine E-mail wird gesendet.';
$string['validemail'] = 'Gültige E-Mail-Adresse';

$string['checkingusage'] = 'Checking plugin usage for courses accessed in the last year';
$string['foundusage'] = 'Found plugin usages';
$string['pluginusagereport'] = 'Plugin Nutzungsbericht';
$string['courseid'] = 'Kurs ID';
$string['usagecount'] = 'Usage Count';
$string['reportgenerated'] = 'Report Generiert';
$string['sendingreport'] = 'Sende den Report an die E-mail';
$string['emailsent'] = 'Nutzungsbericht wurde per E-Mail versendet';
$string['pluginsettings'] = 'Plugin Einstellungen';
$string['email'] = 'E-mail für den Report';
$string['email_desc'] = 'Please enter an email address where the report should be sent.';

// Cronjob Messages
$string['cronjobmessage'] = 'Plugin Nutzungsreporter Cronjob wird ausgeführt';
$string['cronjobnotfound'] = 'Geplante Aufgabe {$a} wurde nicht gefunden';
$string['cronjobregistered'] = 'Geplante Aufgabe {$a} wurde erfolgreich registriert';
$string['cronjobnotfoundexception'] = 'Die geplante Aufgabe konnte nicht registriert werden';
$string['adminusernotfound'] = 'Administratorbenutzer konnte nicht gefunden werden.';

// Settings
$string['emailsetting'] = 'E-Mail-Adresse';
$string['emailsetting_desc'] = 'E-Mail-Adresse, an die der Nutzungsbericht gesendet werden soll';
$string['timeframesetting'] = 'Zeitrahmen (Tage)';
$string['timeframesetting_desc'] = 'Anzahl der Tage in der Vergangenheit, für die Nutzungsdaten gesammelt werden sollen';
$string['frequencysetting'] = 'Berichtsfrequenz';
$string['frequencysetting_desc'] = 'Wie oft soll der Nutzungsbericht generiert werden';
$string['configdaterange'] = 'Datumsbereich für Berichte';
$string['configdaterange_desc'] = 'Wählen Sie den Zeitraum, für den Berichte generiert werden sollen: 3, 6 oder 12 Monate.';
$string['reporttimeframe'] = 'Berichtszeitraum';
$string['reporttimeframe_desc'] = 'Die Anzahl der Tage, die im Plugin-Nutzungsbericht enthalten sein sollen. Muss zwischen 1 und 365 liegen.';

// Frequency options
$string['daily'] = 'Täglich';
$string['weekly'] = 'Wöchentlich';
$string['monthly'] = 'Monatlich';

// Berichtsperioden
$string['month3'] = 'Letzte 3 Monate';
$string['month6'] = 'Letzte 6 Monate';
$string['month12'] = 'Letzte 12 Monate';

// Berichtsspalten
$string['modulename'] = 'Modulname';
$string['coursename'] = 'Kursname';
$string['timestamp'] = 'Zeitstempel';
$string['reportsaved'] = 'Bericht gespeichert';
//Admin Dashboard 
$string['dashboardtitle'] = 'Plugin Nutzungs Dashboard';
$string['action_details'] = 'Details anzeigen';
$string['report_entries'] = 'Einträge';
// Settings
$string['emailsubject'] = 'Plugin-Nutzungsbericht';
$string['emailsubject_desc'] = 'Betreff des E-Mails, das den Nutzungsbericht enthaelt.';
$string['id'] = 'ID';
$string['date'] = 'Datum';
$string['timeframe'] = 'Zeitraum';
$string['entries'] = 'Einträge';
$string['actions'] = 'Aktionen';

// Status and Error Messages
$string['materializedview_unsupported'] = 'Materialized Views not supported in {$a}';
$string['materializedview_missing'] = 'Materialized View not found in database';
$string['materializedview_error'] = 'Materialized View query error';
$string['fallback_raw_query'] = 'Falling back to raw data query';