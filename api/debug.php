<?php
// Diagnostic endpoint - REMOVE AFTER DEBUGGING
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<pre>';
echo 'PHP Version: ' . PHP_VERSION . "\n";
echo 'PHP_VERSION_ID: ' . PHP_VERSION_ID . "\n\n";

echo '--- Extensions ---' . "\n";
echo 'pdo_pgsql: ' . (extension_loaded('pdo_pgsql') ? 'YES' : 'NO') . "\n";
echo 'pdo_mysql: ' . (extension_loaded('pdo_mysql') ? 'YES' : 'NO') . "\n";
echo 'openssl:   ' . (extension_loaded('openssl') ? 'YES' : 'NO') . "\n\n";

echo '--- Directories ---' . "\n";
echo '/tmp writable: ' . (is_writable('/tmp') ? 'YES' : 'NO') . "\n";

$dirs = ['/tmp/storage', '/tmp/storage/framework', '/tmp/storage/framework/views'];
foreach ($dirs as $d) {
    @mkdir($d, 0755, true);
    echo $d . ': ' . (is_dir($d) ? 'EXISTS' : 'MISSING') . "\n";
}

echo "\n--- ENV ---\n";
echo 'APP_KEY set: ' . (getenv('APP_KEY') ? 'YES' : 'NO') . "\n";
echo 'APP_ENV: ' . (getenv('APP_ENV') ?: 'NOT SET') . "\n";
echo 'DB_CONNECTION: ' . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";
echo 'DB_HOST: ' . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo 'SESSION_DRIVER: ' . (getenv('SESSION_DRIVER') ?: 'NOT SET') . "\n";

echo "\n--- Laravel Boot Test ---\n";
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    echo 'File: ' . $e->getFile() . ':' . $e->getLine() . "\n";
    echo 'Trace: ' . $e->getTraceAsString() . "\n";
}
echo '</pre>';
