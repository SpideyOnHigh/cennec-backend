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
        Schema::create('user_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('receiver_user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->longText('message_content');
            $table->enum('status', ['sent', 'delivered', 'read'])
                ->default('sent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_messages');
    }
};
