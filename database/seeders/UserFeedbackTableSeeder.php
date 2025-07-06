<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserFeedback;
use App\Models\UserReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UserFeedbackTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        UserFeedback::truncate();

        UserFeedback::insert([
            [
                'user_id' => 1,
                'rating' => 2,
                'feedback_type_id' => 1,
                'comment' => 'Not Interest',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'rating' => 3,
                'feedback_type_id' => 1,
                'comment' => 'Not Interest 2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        UserFeedback::truncate();
    }
}
