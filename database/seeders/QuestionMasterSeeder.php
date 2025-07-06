<?php

namespace Database\Seeders;

use App\Models\QuestionMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        QuestionMaster::truncate();
        QuestionMaster::insert([
            [
                'question' => 'Why are you looking for cennections?',
                'question_status' => 1,
                'question_orders' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'question' => 'What is your favorite time of year?',
                'question_status' => 1,
                'question_orders' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'question' => 'What was your favorite day?',
                'question_status' => 1,
                'question_orders' => 3,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'question' => 'Describe people with whom you get along best.',
                'question_status' => 1,
                'question_orders' => 4,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        QuestionMaster::truncate();
    }
}
