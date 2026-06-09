<?php
// One-time script — run then DELETE from server
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<pre>";
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output();
echo "Done.";
echo "</pre>";
