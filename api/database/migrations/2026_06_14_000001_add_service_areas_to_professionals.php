<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->json('service_areas')->nullable()->comment('JSON array of service areas: ["Nairobi Central", "Eastlands"]');
            $table->string('current_service_area')->nullable()->comment('Currently selected service area when online');
        });
    }

    public function down(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn(['service_areas', 'current_service_area']);
        });
    }
};
