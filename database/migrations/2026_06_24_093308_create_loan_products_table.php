<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('loan_type', ['personal', 'sme']);
            $table->decimal('minimum_amount', 15, 2);
            $table->decimal('maximum_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2)->comment('Percentage per annum');
            $table->decimal('processing_fee', 10, 2)->default(0);
            $table->decimal('late_fee', 10, 2)->default(0);
            $table->integer('duration_months');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};