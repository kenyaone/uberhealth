<?php
// Check if exec is available
echo "exec: " . (function_exists('exec') ? "YES" : "NO") . "\n";
echo "shell_exec: " . (function_exists('shell_exec') ? "YES" : "NO") . "\n";
echo "system: " . (function_exists('system') ? "YES" : "NO") . "\n";
echo "passthru: " . (function_exists('passthru') ? "YES" : "NO") . "\n";

// Check which php CLI is available
exec('which php 2>&1', $out);
echo "php CLI: " . implode('', $out) . "\n";

exec('php -v 2>&1', $out2);
echo "php version: " . ($out2[0] ?? 'none') . "\n";

// Check if composer is available
exec('which composer 2>&1', $out3);
echo "composer: " . implode('', $out3) . "\n";

// Check missing symfony dir
$root = dirname(__DIR__);
echo "symfony/deprecation-contracts: " . (is_dir($root.'/vendor/symfony/deprecation-contracts') ? "EXISTS" : "MISSING") . "\n";
echo "symfony count: " . count(glob($root.'/vendor/symfony/*')) . " dirs\n";
echo "Total vendor dirs: " . count(glob($root.'/vendor/*')) . "\n";
