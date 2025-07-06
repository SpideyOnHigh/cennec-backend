<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('dob')->nullable();
            $table->enum('gender', [1, 2, 3])->comment('1 = Male, 2 = Female, 3 = Others')->nullable();
            $table->longText('bio')->nullable();
            $table->string('location', 191)->nullable();
            $table->float('location_latitude')->nullable();
            $table->float('location_longitude')->nullable();
            $table->enum('is_smoke', [0, 1, 2])->comment('0 = No, 1 = Yes, 2 = Prefer not to say')->nullable();
            $table->enum('is_drink', [0, 1, 2, 3])->comment('0 = No, 1 = Socially, 2 = Private, 3 = Prefer not to say')->nullable();
            $table->boolean('is_distance_preference')->default(false);
            $table->integer('distance_preference')->nullable();
            $table->boolean('is_age_preference')->default(false);
            $table->integer('from_age_preference')->nullable();
            $table->integer('to_age_preference')->nullable();
            $table->boolean('is_mutual_interest_preference')->default(false);
            $table->integer('min_mutual_interest')->nullable();
            $table->enum('gender_preference', [1, 2, 3])->comment('1 = Male, 2 = Female, 3 = Others')->nullable();
            $table->boolean('is_display_in_search')->default(1);
            $table->boolean('is_display_in_recommendation')->default(1);
            $table->boolean('is_display_location')->default(1);
            $table->boolean('is_display_age')->default(1);
            $table->boolean('is_notification_on')->default(1);
            $table->boolean('is_agree_term_condition')->default(false);
            $table->boolean('is_accept_about_us')->default(false);
            $table->boolean('is_accept_guidelines')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
