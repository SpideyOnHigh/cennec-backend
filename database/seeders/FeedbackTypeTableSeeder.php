<?php

namespace Database\Seeders;

use App\Models\FeedbackTypeMaster;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackTypeTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FeedbackTypeMaster::truncate();

        FeedbackTypeMaster::insert([
            [
                'feedback_title' => 'question',
                'feedback_status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'feedback_title' => 'suggestion',
                'feedback_status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'feedback_title' => 'Informal Feedback',
                'feedback_status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'feedback_title' => 'Peer Feedback',
                'feedback_status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        FeedbackTypeMaster::truncate();
    }
}
