<?php
// One-shot: seeds 7 default support groups. Delete after running.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$groups = [
    [
        'name'        => 'Depression Support',
        'slug'        => 'depression-support',
        'description' => 'A safe space for people navigating depression. Share experiences, coping strategies, and encouragement. Facilitated by a licensed therapist.',
        'category'    => 'depression',
        'icon'        => '💙',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => now(),
        'updated_at'  => now(),
    ],
    [
        'name'        => 'Anxiety & Stress Circle',
        'slug'        => 'anxiety-stress',
        'description' => 'For people dealing with anxiety, panic attacks, and chronic stress. Learn grounding techniques and hear how others cope.',
        'category'    => 'anxiety',
        'icon'        => '🌿',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => now(),
        'updated_at'  => now(),
    ],
    [
        'name'        => 'Alcohol Recovery',
        'slug'        => 'alcohol-recovery',
        'description' => 'Support for people reducing or stopping alcohol use. Share sobriety milestones, challenges, and strategies. Anonymous and non-judgmental.',
        'category'    => 'addiction',
        'icon'        => '🌟',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => now(),
        'updated_at'  => now(),
    ],
    [
        'name'        => 'Overcoming Problem Gambling',
        'slug'        => 'gambling-recovery',
        'description' => 'For people reclaiming their finances and relationships from gambling addiction. A judgement-free zone for honest conversations.',
        'category'    => 'gambling',
        'icon'        => '🎯',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => now(),
        'updated_at'  => now(),
    ],
    [
        'name'        => 'Tobacco & Nicotine Freedom',
        'slug'        => 'tobacco-freedom',
        'description' => 'Support for quitting smoking, vaping, or chewing tobacco. Track your smoke-free days and cheer others on.',
        'category'    => 'tobacco',
        'icon'        => '🚭',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => now(),
        'updated_at'  => now(),
    ],
    [
        'name'        => 'Relationships & Grief',
        'slug'        => 'relationships-grief',
        'description' => 'Processing loss, relationship breakdowns, divorce, or trauma. You are not alone — connect with others who understand.',
        'category'    => 'relationship',
        'icon'        => '💛',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => now(),
        'updated_at'  => now(),
    ],
    [
        'name'        => 'General Wellness',
        'slug'        => 'general-wellness',
        'description' => 'For anyone on a mental wellness journey — mindfulness, self-care, work-life balance, and everyday resilience.',
        'category'    => 'wellness',
        'icon'        => '☀️',
        'is_active'   => 1,
        'member_count'=> 0,
        'created_at'  => now(),
        'updated_at'  => now(),
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
