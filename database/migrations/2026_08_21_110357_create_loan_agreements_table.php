<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_application_id')
                  ->constrained('loan_applications')
                  ->cascadeOnDelete();
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete();

            // Agreement sent to customer
            $table->string('agreement_token')->unique();
            $table->timestamp('agreement_token_expires_at')->nullable();
            $table->boolean('agreement_sent')->default(false);
            $table->timestamp('agreement_sent_at')->nullable();

            // Customer signed agreement upload
            $table->string('signed_file_path')->nullable();
            $table->string('signed_file_original_name')->nullable();
            $table->string('signed_file_type')->nullable(); // pdf/image
            $table->timestamp('signed_submitted_at')->nullable();

            $table->enum('status', [
                'pending',      // agreement sent, awaiting signature
                'submitted',    // customer submitted signed agreement
                'expired',      // 7 days passed, not submitted
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_agreements');
    }
};