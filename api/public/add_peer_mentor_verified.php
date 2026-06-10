<?php
// One-shot: add is_verified column to peer_mentor_profiles
// Delete this file after running

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('peer_mentor_profiles', 'is_verified')) {
    Schema::table('peer_mentor_profiles', function (Blueprint $t) {
        $t->boolean('is_verified')->default(false)->after('is_active');
    });
    echo "Added is_verified column.\n";
} else {
    echo "Column already exists.\n";
}
