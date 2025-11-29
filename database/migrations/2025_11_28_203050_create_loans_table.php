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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con usuario
            $table->foreignId('equipment_id')->constrained('equipment'); // Relación con equipos
            $table->dateTime('loan_date');
            $table->dateTime('expected_return_date');
            $table->dateTime('returned_date')->nullable();
            $table->enum('status', ['active', 'returned', 'late'])->default('active'); // activo, devuelto, mora
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
