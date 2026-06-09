<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$results = [];

try {
    // password_reset_tokens (may already exist in Laravel default)
    if (!Schema::hasTable('password_reset_tokens')) {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        $results[] = 'password_reset_tokens: created';
    } else { $results[] = 'password_reset_tokens: exists'; }

    // peer_mentor_profiles
    if (!Schema::hasTable('peer_mentor_profiles')) {
        Schema::create('peer_mentor_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->text('bio')->nullable();
            $table->json('conditions_helped')->nullable();
            $table->unsignedInteger('years_in_recovery')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        $results[] = 'peer_mentor_profiles: created';
    } else { $results[] = 'peer_mentor_profiles: exists'; }

    // Add is_banned to users if missing
    if (!Schema::hasColumn('users', 'is_banned')) {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_banned')->default(false)->after('role');
        });
        $results[] = 'users.is_banned: added';
    } else { $results[] = 'users.is_banned: exists'; }

    // Add late_cancellation to consultations if missing
    if (!Schema::hasColumn('consultations', 'late_cancellation')) {
        Schema::table('consultations', function (Blueprint $table) {
            $table->boolean('late_cancellation')->default(false)->after('status');
        });
        $results[] = 'consultations.late_cancellation: added';
    } else { $results[] = 'consultations.late_cancellation: exists'; }

    // Add avatar to users if missing
    if (!Schema::hasColumn('users', 'avatar')) {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
        });
        $results[] = 'users.avatar: added';
    } else { $results[] = 'users.avatar: exists'; }

    echo json_encode(['status' => 'ok', 'results' => $results]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage(), 'done' => $results]);
}
