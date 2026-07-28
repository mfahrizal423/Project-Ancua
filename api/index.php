<?php

/**
 * Vercel Serverless - Laravel Bootstrap
 * Sets up /tmp directory structure required by Laravel before booting.
 */

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
