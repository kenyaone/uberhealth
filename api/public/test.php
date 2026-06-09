<?php
echo "PHP OK: " . phpversion() . "\n";
echo "Dir: " . __DIR__ . "\n";
echo "Laravel root: " . dirname(__DIR__) . "\n";

$root = dirname(__DIR__);

// Check key files
$checks = [
    'vendor/autoload.php' => $root . '/vendor/autoload.php',
    '.env' => $root . '/.env',
    'bootstrap/app.php' => $root . '/bootstrap/app.php',
    'artisan' => $root . '/artisan',
];
foreach ($checks as $label => $path) {
    echo "$label: " . (file_exists($path) ? "EXISTS" : "MISSING") . "\n";
}

// Check PHP extensions
foreach (['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'json', 'tokenizer'] as $ext) {
    echo "ext/$ext: " . (extension_loaded($ext) ? "OK" : "MISSING") . "\n";
}

// Try DB connection
$env = file_get_contents($root . '/.env');
preg_match('/DB_DATABASE=(.+)/', $env, $m1);
preg_match('/DB_USERNAME=(.+)/', $env, $m2);
preg_match('/DB_PASSWORD=(.+)/', $env, $m3);

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=" . trim($m1[1] ?? ''),
        trim($m2[1] ?? ''),
        trim($m3[1] ?? '')
    );
    echo "DB connection: OK\n";
} catch (Exception $e) {
    echo "DB connection: FAILED — " . $e->getMessage() . "\n";
}
