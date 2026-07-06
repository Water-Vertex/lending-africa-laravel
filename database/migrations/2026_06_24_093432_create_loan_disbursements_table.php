<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('loan_applications')->restrictOnDelete();
            $table->foreignId('bank_account_id')->constrained('customer_bank_accounts')->restrictOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('reference_no', 100)->unique();
            $table->date('disbursement_date');
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_disbursements');
    }
};