<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_implicit_flush(true);

$root = dirname(__DIR__);
chdir($root);

// Check framework subdirs exist
$dirs = [
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
];
foreach ($dirs as $d) {
    if (!is_dir($root.'/'.$d)) {
        mkdir($root.'/'.$d, 0755, true);
        echo "Created: $d\n";
    } else {
        echo "OK: $d\n";
    }
}

require $root . '/vendor/autoload.php';
echo "autoload loaded\n";

$_ENV['APP_ENV'] = 'production';

try {
    $app = require_once $root . '/bootstrap/app.php';
    echo "app booted\n";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    echo "kernel ready\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
