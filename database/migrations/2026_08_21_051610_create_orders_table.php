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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('industry_id')->constrained('industries')->restrictOnDelete();
            $table->foreignId('workflow_template_id')->constrained('workflow_templates')->restrictOnDelete();
            $table->foreignId('originating_vendor_id')->constrained('vendors')->restrictOnDelete();
            $table->string('status')->default('pending');
            $table->dateTime('deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
