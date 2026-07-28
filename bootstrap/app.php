<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Configure custom storage path if running on serverless (Vercel)
$storagePath = $_ENV['STORAGE_PATH'] ?? $_SERVER['STORAGE_PATH'] ?? getenv('STORAGE_PATH') ?: null;

$builder = Application::configure(basePath: dirname(__DIR__))
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
    });

if ($storagePath) {
    $builder->useStoragePath($storagePath);
}

$app = $builder->create();

return $app;
