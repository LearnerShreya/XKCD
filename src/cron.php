<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$logFile = __DIR__ . '/cron.log';
$logMessage = '[' . date('Y-m-d H:i:s') . '] ';

try {
    if (sendXKCDUpdatesToSubscribers()) {
        $logMessage .= "✅ XKCD updates sent successfully.\n";
    } else {
        throw new Exception("⚠️ Failed to send XKCD updates to some or all subscribers.");
    }
} catch (Exception $e) {
    $logMessage .= "❌ ERROR: " . $e->getMessage() . "\n";
}

file_put_contents($logFile, $logMessage, FILE_APPEND);
echo $logMessage;
