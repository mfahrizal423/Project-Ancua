<?php

/**
 * Vercel Serverless - Laravel Bootstrap
 * Sets up /tmp directory structure required by Laravel before booting.
 */

// ─── DIAGNOSTIC MODE ─────────────────────────────────────────────────────────
// Access /?diag=1 to see PHP environment info without booting Laravel
if (isset($_GET['diag'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header('Content-Type: text/plain; charset=utf-8');

    echo "=== PHP ===\n";
    echo "Version: " . PHP_VERSION . " (ID: " . PHP_VERSION_ID . ")\n\n";

    echo "=== Extensions ===\n";
    foreach (['pdo', 'pdo_pgsql', 'pdo_mysql', 'openssl', 'mbstring', 'curl', 'fileinfo', 'tokenizer', 'xml', 'json'] as $ext) {
        echo str_pad($ext, 12) . ': ' . (extension_loaded($ext) ? 'YES' : 'NO') . "\n";
    }

    echo "\n=== /tmp ===\n";
    echo '/tmp writable: ' . (is_writable('/tmp') ? 'YES' : 'NO') . "\n";
    $testDir = '/tmp/storage/framework/views';
    @mkdir($testDir, 0755, true);
    echo $testDir . ': ' . (is_dir($testDir) ? 'OK' : 'FAILED') . "\n";

    echo "\n=== ENV ===\n";
    foreach (['APP_KEY', 'APP_ENV', 'APP_DEBUG', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'SESSION_DRIVER', 'CACHE_STORE', 'STORAGE_PATH'] as $k) {
        $v = getenv($k) ?: $_ENV[$k] ?? $_SERVER[$k] ?? null;
        if ($v && in_array($k, ['APP_KEY'])) {
            $v = substr($v, 0, 20) . '...';
        }
        echo str_pad($k, 16) . ': ' . ($v ?: 'NOT SET') . "\n";
    }

    echo "\n=== Files ===\n";
    foreach ([
        __DIR__ . '/../public/index.php',
        __DIR__ . '/../vendor/autoload.php',
        __DIR__ . '/../bootstrap/app.php',
    ] as $f) {
        echo str_pad(basename($f), 16) . ': ' . (file_exists($f) ? 'EXISTS' : 'MISSING') . "\n";
    }

    echo "\n=== Laravel Boot ===\n";
    try {
        ob_start();
        require __DIR__ . '/../public/index.php';
        $len = strlen(ob_get_clean());
        echo "OK - output: {$len} bytes\n";
    } catch (\Throwable $e) {
        ob_end_clean();
        echo get_class($e) . ": " . $e->getMessage() . "\n";
        echo "at " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
    exit;
}
// ─────────────────────────────────────────────────────────────────────────────

$tmpBase = '/tmp/storage';

// All directories Laravel needs inside storage/
$dirs = [
    'app',
    'app/public',
    'framework',
    'framework/cache',
    'framework/cache/data',
    'framework/sessions',
    'framework/testing',
    'framework/views',
    'logs',
];

foreach ($dirs as $dir) {
    $path = $tmpBase . '/' . $dir;
    if (!is_dir($path)) {
        @mkdir($path, 0755, true);
    }
}

// Tell Laravel to use /tmp/storage as its storage root
putenv('STORAGE_PATH=' . $tmpBase);
$_ENV['STORAGE_PATH'] = $tmpBase;
$_SERVER['STORAGE_PATH'] = $tmpBase;

// Point view cache to /tmp/storage/framework/views
putenv('VIEW_COMPILED_PATH=' . $tmpBase . '/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = $tmpBase . '/framework/views';
$_SERVER['VIEW_COMPILED_PATH'] = $tmpBase . '/framework/views';

// Ensure APP_KEY is set
if (!getenv('APP_KEY') || getenv('APP_KEY') === '') {
    $key = 'base64:MTgGJ6f24yva1Sg4hS5YBcWLDIyTQ0XbjPjVplPR12E=';
    putenv('APP_KEY=' . $key);
    $_ENV['APP_KEY'] = $key;
    $_SERVER['APP_KEY'] = $key;
}

// Enable debug mode to see real errors
putenv('APP_DEBUG=true');
$_ENV['APP_DEBUG'] = 'true';
$_SERVER['APP_DEBUG'] = 'true';

// Log to stderr (visible in Vercel logs)
putenv('LOG_CHANNEL=stderr');
$_ENV['LOG_CHANNEL'] = 'stderr';
$_SERVER['LOG_CHANNEL'] = 'stderr';

// Use session driver cookie (no file/DB writes needed)
if (!getenv('SESSION_DRIVER') || getenv('SESSION_DRIVER') === '') {
    putenv('SESSION_DRIVER=cookie');
    $_ENV['SESSION_DRIVER'] = 'cookie';
    $_SERVER['SESSION_DRIVER'] = 'cookie';
}

// Use array cache (no DB writes for cache)
if (!getenv('CACHE_STORE') || getenv('CACHE_STORE') === '') {
    putenv('CACHE_STORE=array');
    $_ENV['CACHE_STORE'] = 'array';
    $_SERVER['CACHE_STORE'] = 'array';
}

// Use pgsql (Supabase) as default
if (!getenv('DB_CONNECTION') || getenv('DB_CONNECTION') === '') {
    putenv('DB_CONNECTION=pgsql');
    $_ENV['DB_CONNECTION'] = 'pgsql';
    $_SERVER['DB_CONNECTION'] = 'pgsql';
}

// Forward requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
