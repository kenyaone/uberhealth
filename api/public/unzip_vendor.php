<?php
if (($_GET['key'] ?? '') !== 'UberDeploy2026') { http_response_code(403); die('Forbidden'); }

$root = dirname(__DIR__);
$zip_path = $root . '/vendor.zip';

if (!file_exists($zip_path)) { die("vendor.zip not found at $zip_path\n"); }

echo "Opening zip...\n";
flush();

$zip = new ZipArchive();
if ($zip->open($zip_path) !== true) { die("Failed to open zip\n"); }

echo "Extracting " . $zip->numFiles . " files to $root ...\n";
flush();

$zip->extractTo($root);
$zip->close();

echo "Done. Deleting zip...\n";
unlink($zip_path);

// Verify
echo "symfony/deprecation-contracts: " . (is_dir($root.'/vendor/symfony/deprecation-contracts') ? "OK" : "STILL MISSING") . "\n";
echo "vendor dirs: " . count(glob($root.'/vendor/*')) . "\n";
echo "DELETE THIS FILE after running.\n";
