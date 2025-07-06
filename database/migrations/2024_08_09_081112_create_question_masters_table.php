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
        Schema::create('question_masters', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->enum('question_status', [0, 1])->comment('0 = Inactive, 1 = Active')->default(1);
            $table->integer('question_orders')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_masters');
    }
};
