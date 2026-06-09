<?php
// One-shot: seeds 7 default support groups. Delete after running.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// Create table if not exists
if (!Schema::hasTable('support_groups')) {
    Schema::create('support_groups', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100);
        $table->string('slug', 100)->unique();
        $table->text('description');
        $table->string('category', 50);
        $table->string('icon', 10)->default('💬');
        $table->boolean('is_active')->default(true);
        $table->unsignedInteger('member_count')->default(0);
        $table->timestamps();
    });
}
if (!Schema::hasTable('group_memberships')) {
    Schema::create('group_memberships', function (Blueprint $table) {
        $table->id();
        $table->foreignId('group_id')->constrained('support_groups')->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('display_name', 100)->nullable();
        $table->boolean('is_anonymous')->default(true);
        $table->timestamp('joined_at');
        $table->timestamps();
        $table->unique(['group_id', 'user_id']);
    });
}
if (!Schema::hasTable('group_messages')) {
    Schema::create('group_messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('group_id')->constrained('support_groups')->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->text('content');
        $table->string('display_name', 100);
        $table->boolean('is_pinned')->default(false);
        $table->boolean('is_moderated')->default(false);
        $table->timestamp('moderated_at')->nullable();
        $table->timestamps();
        $table->index(['group_id', 'created_at']);
    });
}

$now = date('Y-m-d H:i:s');

$groups = [
    [
        'name'        => 'Depression Support',
        'slug'        => 'depression-support',
        'description' => 'A safe space for people navigating depression. Share experiences, coping strategies, and encouragement. Facilitated by a licensed therapist.',
        'category'    => 'depression',
        'icon'        => '💙',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => $now,
        'updated_at'  => $now,
    ],
    [
        'name'        => 'Anxiety & Stress Circle',
        'slug'        => 'anxiety-stress',
        'description' => 'For people dealing with anxiety, panic attacks, and chronic stress. Learn grounding techniques and hear how others cope.',
        'category'    => 'anxiety',
        'icon'        => '🌿',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => $now,
        'updated_at'  => $now,
    ],
    [
        'name'        => 'Alcohol Recovery',
        'slug'        => 'alcohol-recovery',
        'description' => 'Support for people reducing or stopping alcohol use. Share sobriety milestones, challenges, and strategies. Anonymous and non-judgmental.',
        'category'    => 'addiction',
        'icon'        => '🌟',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => $now,
        'updated_at'  => $now,
    ],
    [
        'name'        => 'Overcoming Problem Gambling',
        'slug'        => 'gambling-recovery',
        'description' => 'For people reclaiming their finances and relationships from gambling addiction. A judgement-free zone for honest conversations.',
        'category'    => 'gambling',
        'icon'        => '🎯',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => $now,
        'updated_at'  => $now,
    ],
    [
        'name'        => 'Tobacco & Nicotine Freedom',
        'slug'        => 'tobacco-freedom',
        'description' => 'Support for quitting smoking, vaping, or chewing tobacco. Track your smoke-free days and cheer others on.',
        'category'    => 'tobacco',
        'icon'        => '🚭',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => $now,
        'updated_at'  => $now,
    ],
    [
        'name'        => 'Relationships & Grief',
        'slug'        => 'relationships-grief',
        'description' => 'Processing loss, relationship breakdowns, divorce, or trauma. You are not alone — connect with others who understand.',
        'category'    => 'relationship',
        'icon'        => '💛',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => $now,
        'updated_at'  => $now,
    ],
    [
        'name'        => 'General Wellness',
        'slug'        => 'general-wellness',
        'description' => 'For anyone on a mental wellness journey — mindfulness, self-care, work-life balance, and everyday resilience.',
        'category'    => 'wellness',
        'icon'        => '☀️',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => $now,
        'updated_at'  => $now,
    ],
];

$inserted = 0;
foreach ($groups as $g) {
    $exists = DB::table('support_groups')->where('slug', $g['slug'])->exists();
    if (!$exists) {
        DB::table('support_groups')->insert($g);
        $inserted++;
    }
}

echo json_encode(['status' => 'ok', 'inserted' => $inserted, 'message' => "$inserted groups created."]);
