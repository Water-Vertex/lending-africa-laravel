<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('edit_token')->nullable()->unique()->after('status');
            $table->timestamp('edit_token_expires_at')->nullable()->after('edit_token');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['edit_token', 'edit_token_expires_at']);
        });
    }
};