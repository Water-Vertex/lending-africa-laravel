<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('loan_applications')->cascadeOnDelete();
            $table->enum('review_stage', [
                'loan_officer_review',
                'credit_verification',
                'manager_approval',
                'accountant_approval',
                'disbursement'
            ]);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_approvals');
    }
};