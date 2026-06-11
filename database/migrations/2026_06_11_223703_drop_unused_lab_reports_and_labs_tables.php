<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drops the unused lab_reports and labs tables.
     */
    public function up(): void
    {
        Schema::dropIfExists('lab_reports');
        Schema::dropIfExists('labs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('lab_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('labs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->timestamps();
        });
    }
};
