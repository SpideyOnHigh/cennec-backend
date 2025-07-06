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
        Schema::table('invitation_code_masters', function (Blueprint $table) {
            // Add the sponsor_id column with the foreign key constraint
            $table->unsignedBigInteger('sponsor_id')->nullable()->after('code'); // Specify 'after' if needed
            $table->foreign('sponsor_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitation_code_masters', function (Blueprint $table) {
            Schema::table('invitation_code_masters', function (Blueprint $table) {
                $table->dropForeign(['sponsor_id']);
                $table->dropColumn('sponsor_id');
            });
        });
    }
};
