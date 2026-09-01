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
        Schema::table('staff', function (Blueprint $table) {
            // Drop foreign key constraint if exists
            $table->dropForeign(['bank_id']);
            
            // Drop the column
            $table->dropColumn('bank_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            // Add the column back (in case of rollback)
            $table->unsignedBigInteger('bank_id')->nullable()->after('staff_code');
            
            // Add foreign key constraint back
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
        });
    }
};