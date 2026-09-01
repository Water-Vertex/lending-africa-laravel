<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_forget_password', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('token');            // sha256 hash of the plain token sent in email
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_forget_password');
    }
};