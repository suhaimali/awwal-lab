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
        Schema::create('test_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_test_id')->constrained('lab_tests')->onDelete('cascade');
            $table->string('unit')->nullable();
            $table->text('male_reference')->nullable();
            $table->text('female_reference')->nullable();
            $table->decimal('male_min', 10, 2)->nullable();
            $table->decimal('male_max', 10, 2)->nullable();
            $table->decimal('female_min', 10, 2)->nullable();
            $table->decimal('female_max', 10, 2)->nullable();
            $table->boolean('is_immunoassay')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_parameters');
    }
};
