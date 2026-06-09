<?php
if (($_GET['key'] ?? '') !== 'UberDeploy2026') { http_response_code(403); die('Forbidden'); }
header('Content-Type: text/plain');

$root = dirname(__DIR__);
chdir($root);

require $root . '/vendor/autoload.php';

try {
    $app = require_once $root . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "Laravel booted OK\n\n";
} catch (Throwable $e) {
    echo "BOOT ERROR: " . $e->getMessage() . "\n"; exit(1);
}

foreach ([
    ['migrate:fresh', ['--force' => true]],
    ['db:seed',       ['--force' => true]],
    ['config:cache',  []],
    ['route:cache',   []],
] as [$cmd, $args]) {
    echo "=== artisan $cmd ===\n";
    try {
        Artisan::call($cmd, $args);
        echo Artisan::output();
        echo "OK\n\n";
    } catch (Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n\n";
    }
    flush();
}

echo "=== ALL DONE — delete this file ===\n";
