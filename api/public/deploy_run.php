<?php
// One-time deployment script — DELETE THIS FILE after running
// Access: https://api.uberhealth.co.ke/deploy_run.php?key=UberDeploy2026

if (($_GET['key'] ?? '') !== 'UberDeploy2026') {
    http_response_code(403);
    die('Forbidden');
}

define('LARAVEL_ROOT', dirname(__DIR__));
chdir(LARAVEL_ROOT);

function run($cmd) {
    $output = [];
    $code = 0;
    exec("php artisan $cmd --no-interaction 2>&1", $output, $code);
    return ['cmd' => "artisan $cmd", 'output' => implode("\n", $output), 'code' => $code];
}

header('Content-Type: text/plain');
$results = [];
$results[] = run('migrate --force');
$results[] = run('db:seed --force');
$results[] = run('config:cache');
$results[] = run('route:cache');

foreach ($results as $r) {
    echo "=== {$r['cmd']} (exit {$r['code']}) ===\n";
    echo $r['output'] . "\n\n";
}

echo "=== Done. DELETE THIS FILE NOW. ===\n";
