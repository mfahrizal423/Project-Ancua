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

    if (!getenv('LOG_CHANNEL')) {
        putenv('LOG_CHANNEL=stderr');
        $_ENV['LOG_CHANNEL'] = 'stderr';
        $_SERVER['LOG_CHANNEL'] = 'stderr';
    }
}

// Forward requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
