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
        Schema::table('interest_masters', function (Blueprint $table) {
            $table->string('description_link')->nullable();
            $table->unsignedBigInteger('sponsor_id')->nullable();
            $table->foreign('sponsor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interest_masters', function (Blueprint $table) {
            $table->dropForeign(['sponsor_id']);

            $table->dropColumn('description_link');
            $table->dropColumn('sponsor_id');
        });
    }
};
