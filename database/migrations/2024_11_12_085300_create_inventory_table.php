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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->integer('year_model');
            $table->unsignedBigInteger('vehicle_id');
            $table->string('CS_number');
            $table->date('actual_invoice_date');
            $table->date('delivery_date');
            $table->string('invoice_number');
            $table->integer('age');
            $table->string('status');
            $table->string('CS_number_status')->default('available');
            $table->text('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
