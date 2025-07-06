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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('from_user_id')->constrained()->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('from_user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('type')->nullable();
            $table->text('message')->nullable();
            $table->text('request_comment')->nullable();
            $table->text('user_profile_image')->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_connected')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
