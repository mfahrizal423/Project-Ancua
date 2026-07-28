<?php

// Prepare temporary writable storage directories for Vercel serverless environment
$tmpPath = '/tmp';
if (is_writable($tmpPath)) {
    foreach (['views', 'sessions', 'cache', 'logs'] as $dir) {
        $path = $tmpPath . '/' . $dir;
        if (!file_exists($path)) {
            @mkdir($path, 0777, true);
        }
    }
    putenv('VIEW_COMPILED_PATH=' . $tmpPath . '/views');
    $_ENV['VIEW_COMPILED_PATH'] = $tmpPath . '/views';
    $_SERVER['VIEW_COMPILED_PATH'] = $tmpPath . '/views';
}

if (!getenv('APP_KEY')) {
    putenv('APP_KEY=base64:MTgGJ6f24yva1Sg4hS5YBcWLDIyTQ0XbjPjVplPR12E=');
    $_ENV['APP_KEY'] = 'base64:MTgGJ6f24yva1Sg4hS5YBcWLDIyTQ0XbjPjVplPR12E=';
    $_SERVER['APP_KEY'] = 'base64:MTgGJ6f24yva1Sg4hS5YBcWLDIyTQ0XbjPjVplPR12E=';
}

if (!getenv('APP_DEBUG')) {
    putenv('APP_DEBUG=true');
    $_ENV['APP_DEBUG'] = 'true';
    $_SERVER['APP_DEBUG'] = 'true';
}

if (!getenv('LOG_CHANNEL')) {
    putenv('LOG_CHANNEL=stderr');
    $_ENV['LOG_CHANNEL'] = 'stderr';
    $_SERVER['LOG_CHANNEL'] = 'stderr';
}

if (!getenv('DB_CONNECTION')) {
    putenv('DB_CONNECTION=pgsql');
    $_ENV['DB_CONNECTION'] = 'pgsql';
    $_SERVER['DB_CONNECTION'] = 'pgsql';
}

// Forward requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
