<?php

namespace Database\Seeders;

use App\Models\InterestMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        InterestMaster::truncate();
        InterestMaster::insert([
            [
                'interest_name' => 'Genres & Artists',
                'interest_color' => '#FF5733',
                'interest_category_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'interest_name' => 'Music Theory & History',
                'interest_color' => '#33FF57',
                'interest_category_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'interest_name' => 'Team Sports',
                'interest_color' => '#3357FF',
                'interest_category_id' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'interest_name' => 'Individual Sports',
                'interest_color' => '#FF33A1',
                'interest_category_id' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'interest_name' => 'Sports Events & News',
                'interest_color' => '#F0E68C',
                'interest_category_id' => '2',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'interest_name' => 'Destinations & Attractions',
                'interest_color' => '#F0E68C',
                'interest_category_id' => '3',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'interest_name' => 'Cultural Experiences',
                'interest_color' => '#F0E68C',
                'interest_category_id' => '3',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        InterestMaster::truncate();
    }
}
