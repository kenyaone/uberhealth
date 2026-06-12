<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('professionals', function (Blueprint $table) {
            $table->string('location_city')->nullable()->after('profile_photo');
            $table->string('location_county')->nullable()->after('location_city');
            $table->decimal('latitude', 10, 7)->nullable()->after('location_county');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('tribe')->nullable()->after('longitude');
            $table->date('date_of_birth')->nullable()->after('tribe');
            $table->string('cpb_license')->nullable()->unique()->after('date_of_birth');
            $table->boolean('is_available_physical')->default(false)->after('cpb_license');
        });
    }

    public function down(): void {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn([
                'location_city',
                'location_county',
                'latitude',
                'longitude',
                'tribe',
                'date_of_birth',
                'cpb_license',
                'is_available_physical'
            ]);
        });
    }
};
