<?php
// Simple PHP diagnostic - no Laravel
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain');

echo "=== PHP Info ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "PHP_VERSION_ID: " . PHP_VERSION_ID . "\n\n";

echo "=== Extensions ===\n";
$exts = ['pdo', 'pdo_pgsql', 'pdo_mysql', 'openssl', 'mbstring', 'curl', 'fileinfo', 'tokenizer', 'xml'];
foreach ($exts as $ext) {
    echo $ext . ': ' . (extension_loaded($ext) ? 'YES' : 'NO') . "\n";
}

echo "\n=== /tmp Writable ===\n";
echo '/tmp: ' . (is_writable('/tmp') ? 'YES' : 'NO') . "\n";
$testDir = '/tmp/storage/framework/views';
@mkdir($testDir, 0755, true);
echo $testDir . ': ' . (is_dir($testDir) ? 'CREATED' : 'FAILED') . "\n";

echo "\n=== ENV Variables ===\n";
$keys = ['APP_KEY', 'APP_ENV', 'APP_DEBUG', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'SESSION_DRIVER', 'CACHE_STORE'];
foreach ($keys as $k) {
    $val = getenv($k);
    if ($k === 'APP_KEY' && $val) {
        $val = substr($val, 0, 20) . '...';
    }
    if ($k === 'DB_PASSWORD' && $val) {
        $val = '***';
    }
    echo $k . ': ' . ($val ?: 'NOT SET') . "\n";
}

echo "\n=== Files Check ===\n";
$files = [
    __DIR__ . '/../public/index.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../bootstrap/app.php',
];
foreach ($files as $f) {
    echo basename($f) . ': ' . (file_exists($f) ? 'EXISTS' : 'MISSING') . "\n";
}

echo "\n=== Laravel Boot ===\n";
try {
    ob_start();
    require __DIR__ . '/../public/index.php';
    $output = ob_get_clean();
    echo "Boot OK. Output length: " . strlen($output) . "\n";
} catch (\Throwable $e) {
    ob_end_clean();
    echo "BOOT ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Type: " . get_class($e) . "\n";
}
