<?php
// One-time script — run then DELETE from server
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<pre>";
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output();

// Seed support groups if not already seeded
if (\App\Models\SupportGroup::count() === 0) {
    $groups = [
        ['name'=>'Depression & Mood Support','slug'=>'depression-mood','description'=>'A safe space to share experiences with depression, low mood, and emotional struggles. You are not alone.','category'=>'depression','icon'=>'💙'],
        ['name'=>'Anxiety Warriors','slug'=>'anxiety-warriors','description'=>'Support for anxiety, panic attacks, worry and stress. Share coping strategies and encouragement.','category'=>'anxiety','icon'=>'🌿'],
        ['name'=>'Addiction Recovery Circle','slug'=>'addiction-recovery','description'=>'For those on the journey of recovery from substance use. Peer support, milestones, and accountability.','category'=>'addiction','icon'=>'🔥'],
        ['name'=>'Gambling Recovery','slug'=>'gambling-recovery','description'=>'Support for gambling addiction — financial recovery, family repair, and staying clean.','category'=>'gambling','icon'=>'♟️'],
        ['name'=>'Tobacco Cessation','slug'=>'tobacco-cessation','description'=>'Quitting smoking or tobacco. Share tips, track quit dates, and celebrate milestones together.','category'=>'tobacco','icon'=>'🚭'],
        ['name'=>'Grief & Loss','slug'=>'grief-loss','description'=>'Processing loss — of a loved one, a relationship, or a life chapter. Hold space for each other here.','category'=>'grief','icon'=>'🕊️'],
        ['name'=>'Relationship & Family','slug'=>'relationship-family','description'=>'Navigating relationship difficulties, family conflict, and rebuilding connection.','category'=>'relationship','icon'=>'❤️'],
    ];
    foreach ($groups as $g) {
        \App\Models\SupportGroup::create(array_merge($g, ['is_active'=>true,'member_count'=>0]));
    }
    echo "Seeded " . count($groups) . " support groups.\n";
}

echo "Done.";
echo "</pre>";
