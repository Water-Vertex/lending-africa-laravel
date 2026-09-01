<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_amounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();

            $table->decimal('loan_amount', 15, 2);
            $table->integer('duration_months');
            $table->decimal('interest_rate', 5, 2);

            $table->decimal('monthly_payment', 15, 2);
            $table->decimal('total_payment', 15, 2);
            $table->decimal('total_interest', 15, 2);

            $table->enum('status', ['submitted', 'under_review', 'approved', 'rejected', 'disbursed', 'closed'])
                  ->default('submitted');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_amounts');
    }
};