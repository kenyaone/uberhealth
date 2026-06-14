<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add default service areas to existing sample professionals
        $serviceAreas = [
            1 => ['Nairobi Central', 'Westlands', 'Upper Hill'],
            2 => ['Mombasa', 'Mombasa CBD'],
            3 => ['Kisumu', 'Kisumu CBD'],
            4 => ['Nakuru', 'Nakuru CBD'],
            5 => ['Nairobi', 'Karen', 'Lavington'],
            6 => ['Nairobi', 'South B', 'South C'],
        ];

        foreach ($serviceAreas as $profId => $areas) {
            DB::table('professionals')
                ->where('id', $profId)
                ->update(['service_areas' => json_encode($areas)]);
        }
    }

    public function down(): void
    {
        DB::table('professionals')->update(['service_areas' => null]);
    }
};
