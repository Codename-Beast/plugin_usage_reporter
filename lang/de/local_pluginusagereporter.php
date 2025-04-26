<?php
/**
 * Deutsche Sprachdatei für Plugin Usage Reporter
 *
 * @package    local_pluginusagereporter
 * @copyright  2024 Bernd Schreistetter
 * @license    MIT https://opensource.org/licenses/MIT
 */

defined('MOODLE_INTERNAL') || die();

// Core
$string['pluginname'] = 'Plugin Usage Reporter';
$string['taskname'] = 'Generate Usage Report Task';

// General Settings
$string['generalsettings'] = 'Allgemeine Einstellungen';
$string['timeframe'] = 'Berichtszeitraum (Tage)';
$string['timeframe_desc'] = 'Anzahl der Tage, die im Bericht berücksichtigt werden sollen.';
$string['itemsperpage'] = 'Einträge pro Seite';
$string['itemsperpage_desc'] = 'Anzahl der Berichtseinträge, die pro Seite im Dashboard angezeigt werden.';
$string['includehidden'] = 'Versteckte Kurse einbeziehen';
$string['includehidden_desc'] = 'Versteckte Kurse in die Nutzungsstatistiken einbeziehen.';
$string['enabledebug'] = 'Debug-Modus aktivieren';
$string['enabledebug_desc'] = 'Detaillierte Protokollierung und Entwicklermeldungen aktivieren (nur für Entwicklungsumgebungen empfohlen).';

// Caching
$string['enablecaching'] = 'Caching aktivieren';
$string['enablecaching_desc'] = 'Berichtsdaten zwischenspeichern, um die Leistung zu verbessern.';
$string['cachettl'] = 'Cache-Lebensdauer';
$string['cachettl_desc'] = 'Zeit in Sekunden, bis der Cache abläuft.';

// Tasks
$string['tasksettings'] = 'Geplante Aufgaben & Wiederholungen';
$string['enablescheduledtask'] = 'Geplante Aufgabe aktivieren';
$string['enablescheduledtask_desc'] = 'Berichte automatisch über den Cron generieren.';
$string['taskretries'] = 'Wiederholungsversuche bei Fehlern';
$string['taskretries_desc'] = 'Anzahl der Wiederholungsversuche bei fehlgeschlagenen Aufgaben.';
$string['retrydelay'] = 'Wartezeit zwischen Wiederholungen';
$string['retrydelay_desc'] = 'Verzögerung in Sekunden zwischen Wiederholungsversuchen.';

// API Settings
$string['apisettings'] = 'API Einstellungen';
$string['enableexternalapi'] = 'Externe API aktivieren';
$string['enableexternalapi_desc'] = 'Integration mit externen Monitoringsystemen aktivieren.';
$string['externalapiurl'] = 'API-Endpunkt URL';
$string['externalapiurl_desc'] = 'Nur HTTPS-Verbindungen sind zulässig.';

// Status (optional)
$string['status_starting'] = 'Berichtserstellung wird gestartet...';
$string['status_complete'] = 'Berichtserstellung abgeschlossen';
$string['status_failed'] = 'Berichtserstellung fehlgeschlagen';
$string['status_retrying'] = 'Wiederhole fehlgeschlagene Aufgabe...';

// Output Types (optional)
$string['reporttype_html'] = 'HTML-Bericht';
$string['reporttype_csv'] = 'CSV-Export';
$string['reporttype_xml'] = 'XML-Feed';

// Fehler-/Validierungsmeldungen
$string['generation_error'] = 'Die Berichtserstellung ist fehlgeschlagen. Bitte kontaktiere den Administrator.';
$string['https_required'] = 'Nur sichere HTTPS-Endpunkte sind erlaubt.';
$string['error_invalidtimeframe'] = 'Ungültiger Zeitraum: Der Wert muss größer als 0 sein.';

// Fehlermeldungen
$string['error_invalidtimeframe'] = 'Ungültiger Zeitraum angegeben.';
$string['error_invalidformat'] = 'Ungültiges Format angegeben.';
$string['error_db'] = 'Beim Abrufen der Plugin-Nutzungsdaten ist ein Datenbankfehler aufgetreten.';
$string['error_unauthorized'] = 'Unberechtigter Zugriff. Sie haben keine Berechtigung, Plugin-Nutzungsdaten anzuzeigen.';
$string['error_rate_limit'] = 'Rate-Limit überschritten. Bitte versuchen Sie es später erneut.';
$string['error_missing_apikey'] = 'API-Schlüssel fehlt oder ist ungültig.';

// Einstellungen
$string['enable_notifications'] = 'E-Mail-Benachrichtigungen aktivieren';
$string['enable_notifications_desc'] = 'Wenn aktiviert, sendet das System Benachrichtigungen über die Ergebnisse der Berichtserstellung per E-Mail.';

$string['notification_email'] = 'Empfänger E-Mail-Adresse für Benachrichtigungen';
$string['notification_email_desc'] = 'Die E-Mail-Adresse, an die Benachrichtigungen über die Berichtserstellung gesendet werden sollen.';

$string['external_api_key'] = 'Externer API-Schlüssel';
$string['external_api_key_desc'] = 'API-Schlüssel, der zur Authentifizierung externer API-Anfragen an den Bericht benötigt wird.';

$string['external_api_url'] = 'Externe API-URL';
$string['external_api_url_desc'] = 'Die URL des externen Systems, an das die Plugin-Nutzungsdaten gesendet werden sollen.';
// Datenschutz-Strings
$string['privacy:metadata:log'] = 'Speichert Protokolleinträge im Zusammenhang mit der Erstellung von Plugin-Nutzungsberichten.';
$string['privacy:metadata:log:level'] = 'Das Protokollniveau (Info, Warnung, Fehler) des Eintrags.';
$string['privacy:metadata:log:message'] = 'Der Nachrichteninhalt des Protokolleintrags.';
$string['privacy:metadata:log:timecreated'] = 'Die Zeit, zu der der Protokolleintrag erstellt wurde.';
