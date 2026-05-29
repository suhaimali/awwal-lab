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
        Schema::table('test_parameters', function (Blueprint $table) {
            $table->text('biological_reference')->nullable()->after('female_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_parameters', function (Blueprint $table) {
            $table->dropColumn('biological_reference');
        });
    }
};
