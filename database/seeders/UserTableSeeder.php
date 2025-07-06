<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        UserDetail::truncate();
        $admin = User::create([
            'name' => 'Cennec Admin',
            'username' => 'cennecadmin',
            'email' => 'cennec.admin@yopmail.com',
            'password' => bcrypt('Test105*'),
            'apple_id' => null,
            'google_id' => null,
            'fcm_token' => null,
            'email_verified_at' => now(),
            'invitation_code_id' => 1,
            'email_otp' => null,
            'user_status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        $admin->assignRole('Super Admin');

        User::insert([
            [
                'id' => 2,
                'name' => 'Test User',
                'username' => 'testuser',
                'email' => 'testuser@yopmail.com',
                'password' => bcrypt('Test105*'),
                'apple_id' => null,
                'google_id' => null,
                'fcm_token' => null,
                'email_verified_at' => now(),
                'invitation_code_id' => 1,
                'email_otp' => null,
                'user_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'name' => 'Test User 2',
                'username' => 'testuser2',
                'email' => 'testuser2@yopmail.com',
                'password' => bcrypt('Test105*'),
                'apple_id' => null,
                'google_id' => null,
                'fcm_token' => null,
                'email_verified_at' => now(),
                'invitation_code_id' => 1,
                'email_otp' => null,
                'user_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'name' => 'Test Admin',
                'username' => 'testadmin',
                'email' => 'testadmin@yopmail.com',
                'password' => bcrypt('Test105*'),
                'apple_id' => null,
                'google_id' => null,
                'fcm_token' => null,
                'email_verified_at' => now(),
                'invitation_code_id' => 1,
                'email_otp' => null,
                'user_status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);

        $user1 = User::find(2);
        $user2 = User::find(3);
        $user3 = User::find(4);

        $user1->assignRole('App User');
        $user2->assignRole('App User');
        $user3->assignRole('Admin');

        UserDetail::insert([
            [
                'user_id' => 2,
                'dob' => '1990-01-01',
                'gender' => 1,
                'bio' => null,
                'location' => null,
                'location_latitude' => null,
                'location_longitude' => null,
                'is_smoke' => null,
                'is_drink' => null,
                'is_distance_preference' => false,
                'distance_preference' => 50,
                'is_age_preference' => false,
                'from_age_preference' => 18,
                'to_age_preference' => 100,
                'is_mutual_interest_preference' => false,
                'min_mutual_interest' => 10,
                'gender_preference' => null,
                'is_display_in_search' => true,
                'is_display_in_recommendation' => true,
                'is_display_location' => true,
                'is_display_age' => true,
                'is_notification_on' => true,
                'is_agree_term_condition' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'dob' => '1985-05-15',
                'gender' => 2,
                'bio' => null,
                'location' => null,
                'location_latitude' => null,
                'location_longitude' => null,
                'is_smoke' => null,
                'is_drink' => null,
                'is_distance_preference' => false,
                'distance_preference' => 50,
                'is_age_preference' => false,
                'from_age_preference' => 18,
                'to_age_preference' => 100,
                'is_mutual_interest_preference' => false,
                'min_mutual_interest' => 10,
                'gender_preference' => null,
                'is_display_in_search' => true,
                'is_display_in_recommendation' => true,
                'is_display_location' => true,
                'is_display_age' => true,
                'is_notification_on' => true,
                'is_agree_term_condition' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
    }
}
