<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code', 50)->unique();
            $table->enum('customer_type', ['personal', 'sme']);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('national_id', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone_primary', 20);
            $table->string('phone_secondary', 20)->nullable();
            $table->string('occupation', 150)->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->string('country', 100)->default('Nigeria');
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('local_government_area', 100)->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active', 'inactive', 'blacklisted'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};