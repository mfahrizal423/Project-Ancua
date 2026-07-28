<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            'api/duitku/callback'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

if (isset($_ENV['STORAGE_PATH']) || isset($_SERVER['STORAGE_PATH'])) {
    $storagePath = $_ENV['STORAGE_PATH'] ?? $_SERVER['STORAGE_PATH'];
    $app->useStoragePath($storagePath);
}

return $app;
