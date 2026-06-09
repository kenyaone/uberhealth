<?php
// One-shot: creates the insurance_claims table. Delete after running.

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (Schema::hasTable('insurance_claims')) {
    echo json_encode(['status' => 'already_exists', 'message' => 'insurance_claims table already exists']);
    exit;
}

Schema::create('insurance_claims', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('consultation_id');
    $table->unsignedBigInteger('user_id');
    $table->string('claim_reference', 50)->unique();
    $table->string('provider', 100);
    $table->string('member_number', 100);
    $table->string('id_number', 50);
    $table->string('scheme_name', 100)->nullable();
    $table->decimal('amount', 10, 2);
    $table->enum('status', ['pending', 'submitted', 'approved', 'rejected', 'co_payment_required'])->default('pending');
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->foreign('consultation_id')->references('id')->on('consultations')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});

echo json_encode(['status' => 'ok', 'message' => 'insurance_claims table created successfully']);
