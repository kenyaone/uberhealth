<?php
$root = dirname(__DIR__);
chdir($root);

// Check storage permissions
echo "storage/logs writable: " . (is_writable($root.'/storage/logs') ? "YES" : "NO") . "\n";
echo "storage/framework writable: " . (is_writable($root.'/storage/framework') ? "YES" : "NO") . "\n";
echo "bootstrap/cache writable: " . (is_writable($root.'/bootstrap/cache') ? "YES" : "NO") . "\n";

// Try loading autoload
require $root . '/vendor/autoload.php';
echo "autoload: OK\n";

// Try booting Laravel
try {
    $app = require_once $root . '/bootstrap/app.php';
    echo "bootstrap: OK\n";
} catch (Throwable $e) {
    echo "bootstrap ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
