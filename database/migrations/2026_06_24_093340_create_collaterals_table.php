<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collaterals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('loan_applications')->cascadeOnDelete();
            $table->foreignId('collateral_type_id')->constrained('collateral_types')->restrictOnDelete();
            $table->string('asset_name', 200);
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->string('ownership_document_no', 100)->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collaterals');
    }
};