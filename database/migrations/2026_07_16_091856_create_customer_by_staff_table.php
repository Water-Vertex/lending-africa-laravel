<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_by_staff', function (Blueprint $table) {
            $table->id();
            
            // Customer foreign keys
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('customer_code', 50);
            
            // Staff foreign keys
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->string('staff_code', 20);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_by_staff');
    }
};