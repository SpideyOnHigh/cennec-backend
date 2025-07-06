<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UserReportsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        UserReport::truncate();

        UserReport::insert([
            [
                'reported_by_user_id' => 2,
                'reported_user_id' => 1,
                'reason' => 'Test Cennec 001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        UserReport::truncate();
    }
}
