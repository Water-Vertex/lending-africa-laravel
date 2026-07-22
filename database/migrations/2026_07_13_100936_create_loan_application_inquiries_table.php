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
        Schema::create('loan_application_inquiries', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');

            $table->string('email');
            $table->string('phone');

            $table->string('loan_type');

            $table->decimal('loan_amount', 15, 2);

            $table->string('preferred_bank');

            $table->text('loan_purpose');

            // Email Verification
            $table->string('token')->unique();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            // Status
            $table->enum('status', [
                'pending',
                'email_sent',
                'verified',
                'completed',
                'expired'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_application_inquiries');
    }
};