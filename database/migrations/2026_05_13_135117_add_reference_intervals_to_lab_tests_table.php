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
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->string('unit')->nullable();
            $table->text('male_reference')->nullable();
            $table->text('female_reference')->nullable();
            $table->decimal('male_min', 10, 2)->nullable();
            $table->decimal('male_max', 10, 2)->nullable();
            $table->decimal('female_min', 10, 2)->nullable();
            $table->decimal('female_max', 10, 2)->nullable();
            $table->boolean('is_immunoassay')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropColumn(['unit', 'male_reference', 'female_reference', 'male_min', 'male_max', 'female_min', 'female_max', 'is_immunoassay']);
        });
    }
};
