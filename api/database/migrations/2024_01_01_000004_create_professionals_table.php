<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kmpdc_license')->unique();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->decimal('rate_per_hour', 10, 2)->default(2000);
            $table->text('bio');
            $table->integer('years_experience')->default(0);
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_sessions')->default(0);
            $table->integer('total_reviews')->default(0);
            $table->boolean('is_available_online')->default(true);
            $table->boolean('is_accepting_new_patients')->default(true);
            $table->string('profile_photo')->nullable();
            $table->string('mpesa_number', 15)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
};
