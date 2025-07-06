<?php

namespace Database\Seeders;

use App\Models\InvitationCodeMaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvitationCodeMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        InvitationCodeMaster::truncate();
        InvitationCodeMaster::insert([
            [
                'code' => 'INVITE2024A',
                'max_user_allow' => 10,
                'sponsor_id' => 1,
                'expiration_date' => '2024-12-31',
                'comment' => 'First batch of invites for early users.',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'code' => 'INVITE2024B',
                'max_user_allow' => 5,
                'sponsor_id' => 1,
                'expiration_date' => '2024-11-30',
                'comment' => 'Limited invitation code for friends.',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'code' => 'INVITE2024C',
                'max_user_allow' => 20,
                'sponsor_id' => 1,
                'expiration_date' => '2025-01-15',
                'comment' => 'Invite for new users joining the platform.',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'code' => 'INVITE2024D',
                'max_user_allow' => 30,
                'sponsor_id' => 1,
                'expiration_date' => '2025-01-15',
                'comment' => 'Open invitation code for anyone.',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        InvitationCodeMaster::truncate();
    }
}
