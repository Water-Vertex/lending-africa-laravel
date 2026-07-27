<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('co_signers', function (Blueprint $table) {
            $table->string('photo_id')->nullable()->after('phone_secondary');
            $table->string('city', 100)->nullable()->after('address');
            $table->string('state', 100)->nullable()->after('city');
            $table->string('country', 100)->nullable()->after('state');
            $table->string('evidence_of_occupation')->nullable()->after('occupation');
            $table->string('bvn', 20)->nullable()->after('country');
        });
    }

    public function down(): void
    {
        Schema::table('co_signers', function (Blueprint $table) {
            $table->dropColumn(['photo_id', 'city', 'state', 'country', 'evidence_of_occupation', 'bvn']);
        });
    }
};