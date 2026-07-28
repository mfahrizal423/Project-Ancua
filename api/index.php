<?php

/**
 * Vercel Serverless - Laravel Bootstrap
 * Sets up /tmp directory structure required by Laravel before booting.
 */

// 1. Prepare /tmp writable directories for Storage and Bootstrap Cache
$tmpBase = '/tmp/storage';
$tmpCache = '/tmp/bootstrap/cache';

$dirs = [
    $tmpBase . '/app',
    $tmpBase . '/app/public',
    $tmpBase . '/framework',
    $tmpBase . '/framework/cache',
    $tmpBase . '/framework/cache/data',
    $tmpBase . '/framework/sessions',
    $tmpBase . '/framework/testing',
    $tmpBase . '/framework/views',
    $tmpBase . '/logs',
    $tmpCache,
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Set environment variables BEFORE any Laravel file is loaded
putenv('STORAGE_PATH=' . $tmpBase);
$_ENV['STORAGE_PATH'] = $tmpBase;
$_SERVER['STORAGE_PATH'] = $tmpBase;

putenv('VIEW_COMPILED_PATH=' . $tmpBase . '/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = $tmpBase . '/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $tmpBase . '/framework/views';

// Direct Laravel manifest caches to /tmp
putenv('APP_SERVICES_CACHE=' . $tmpCache . '/services.php');
$_ENV['APP_SERVICES_CACHE'] = $tmpCache . '/services.php';
$_SERVER['APP_SERVICES_CACHE'] = $tmpCache . '/services.php';

putenv('APP_PACKAGES_CACHE=' . $tmpCache . '/packages.php');
$_ENV['APP_PACKAGES_CACHE'] = $tmpCache . '/packages.php';
$_SERVER['APP_PACKAGES_CACHE'] = $tmpCache . '/packages.php';

putenv('APP_CONFIG_CACHE=' . $tmpCache . '/config.php');
$_ENV['APP_CONFIG_CACHE'] = $tmpCache . '/config.php';
$_SERVER['APP_CONFIG_CACHE'] = $tmpCache . '/config.php';

putenv('APP_ROUTES_CACHE=' . $tmpCache . '/routes.php');
$_ENV['APP_ROUTES_CACHE'] = $tmpCache . '/routes.php';
$_SERVER['APP_ROUTES_CACHE'] = $tmpCache . '/routes.php';

putenv('APP_EVENTS_CACHE=' . $tmpCache . '/events.php');
$_ENV['APP_EVENTS_CACHE'] = $tmpCache . '/events.php';
$_SERVER['APP_EVENTS_CACHE'] = $tmpCache . '/events.php';

// Force log to stderr (Vercel console) so Monolog never attempts writing to /var/task/user/storage/logs/laravel.log
putenv('LOG_CHANNEL=stderr');
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';

// Key, session, cache, and db fallbacks
if (!getenv('APP_KEY') || getenv('APP_KEY') === '') {
    $key = 'base64:MTgGJ6f24yva1Sg4hS5YBcWLDIyTQ0XbjPjVplPR12E=';
    putenv('APP_KEY=' . $key);
    $_ENV['APP_KEY'] = $key;
    $_SERVER['APP_KEY'] = $key;
}

putenv('APP_DEBUG=true');
$_ENV['APP_DEBUG'] = 'true';
$_SERVER['APP_DEBUG'] = 'true';

if (!getenv('SESSION_DRIVER') || getenv('SESSION_DRIVER') === '') {
    putenv('SESSION_DRIVER=cookie');
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_SERVER['SESSION_DRIVER'] = 'cookie';
}

if (!getenv('CACHE_STORE') || getenv('CACHE_STORE') === '') {
    putenv('CACHE_STORE=array');
    $_ENV['CACHE_STORE'] = 'array';
    $_SERVER['CACHE_STORE'] = 'array';
}

if (!getenv('DB_CONNECTION') || getenv('DB_CONNECTION') === '') {
    putenv('DB_CONNECTION=pgsql');
    $_ENV['DB_CONNECTION'] = 'pgsql';
    $_SERVER['DB_CONNECTION'] = 'pgsql';
}

// ─── DIAGNOSTIC MODE ─────────────────────────────────────────────────────────
if (isset($_GET['diag'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: text/plain; charset=utf-8');

    echo "=== PHP ===\n";
    echo "Version: " . PHP_VERSION . " (ID: " . PHP_VERSION_ID . ")\n\n";

    echo "=== /tmp & Paths ===\n";
    echo 'STORAGE_PATH: ' . getenv('STORAGE_PATH') . "\n";
    echo 'APP_SERVICES_CACHE: ' . getenv('APP_SERVICES_CACHE') . "\n";
    echo 'LOG_CHANNEL: ' . getenv('LOG_CHANNEL') . "\n";

    echo "\n=== Laravel Boot Test ===\n";
    try {
        ob_start();
        require __DIR__ . '/../public/index.php';
        $len = strlen(ob_get_clean());
        echo "BOOT SUCCESS! Response length: {$len} bytes\n";
    } catch (\Throwable $e) {
        ob_end_clean();
        echo "BOOT ERROR: " . get_class($e) . ": " . $e->getMessage() . "\n";
        echo "At: " . $e->getFile() . ":" . $e->getLine() . "\n";
        echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
    }
    exit;
}
// ─────────────────────────────────────────────────────────────────────────────

// Forward requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
