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
            $table->unsignedBigInteger('interest_category_id')->nullable()->after('interest_color'); // Specify 'after' if needed
            $table->foreign('interest_category_id')
                ->references('id')
                ->on('interest_category_masters')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interest_masters', function (Blueprint $table) {
            $table->dropForeign(['interest_category_id']);
            $table->dropColumn('interest_category_id');
        });
    }
};
