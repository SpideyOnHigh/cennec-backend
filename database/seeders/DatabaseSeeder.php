<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(LanguageSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(AdminPermissionSeeder::class);
        $this->call(InterestMasterSeeder::class);
        $this->call(InterestCategoryMasterSeeder::class);
        $this->call(InvitationCodeMasterSeeder::class);
        $this->call(QuestionMasterSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(FeedbackTypeTableSeeder::class);
        $this->call(PolicySeeder::class);
    }
}
