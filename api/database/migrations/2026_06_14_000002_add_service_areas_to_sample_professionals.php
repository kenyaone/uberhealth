<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add default service areas to existing sample professionals
        $serviceAreas = [
            1 => ['Westlands - Chiromo Road', 'Westlands - Mpesi Lane', 'Upper Hill - Limuru Road'],
            2 => ['Mombasa - Nkrumah Road', 'Mombasa - Jomo Kenyatta Avenue'],
            3 => ['Kisumu - Oginga Odinga Street', 'Kisumu - Kenyatta Avenue'],
            4 => ['Nakuru - Kenyatta Avenue', 'Nakuru - Jomo Kenyatta Street'],
            5 => ['Upper Hill - Limuru Road', 'Karen - Karen Road', 'Lavington - Valley Road'],
            6 => ['Kilimani - Ngong Road', 'South B - Langata Road', 'South C - Mombasa Road'],
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
