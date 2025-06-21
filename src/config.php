<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('UTC');
define('LOG_FILE', __DIR__ . '/app.log');

function log_message($message) {
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents(LOG_FILE, "[$timestamp] $message\n", FILE_APPEND);
}

// SMTP Config
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', getenv('SMTP_USERNAME') ?: null);
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: null);
define('FROM_EMAIL', SMTP_USERNAME ?: 'no-reply@example.com');
define('FROM_NAME', 'XKCD Updates');