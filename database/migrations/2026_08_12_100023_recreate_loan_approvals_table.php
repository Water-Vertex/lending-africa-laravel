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
            $table->foreignId('application_id')
                  ->constrained('loan_applications')
                  ->cascadeOnDelete();
            $table->foreignId('actioned_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->enum('action', [
                'approved',
                'rejected',
                'additional_info_requested',
            ]);
            $table->text('message')->nullable();   
            $table->text('remarks')->nullable();   
            $table->timestamp('actioned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_approvals');
    }
};