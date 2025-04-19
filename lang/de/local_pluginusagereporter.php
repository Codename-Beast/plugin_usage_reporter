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

