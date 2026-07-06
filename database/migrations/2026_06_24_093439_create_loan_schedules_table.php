<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('loan_applications')->cascadeOnDelete();
            $table->integer('total_installments');
            $table->decimal('total_interest', 15, 2);
            $table->decimal('total_payable', 15, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_schedules');
    }
};