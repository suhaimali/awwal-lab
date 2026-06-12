<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * - Drop the unique index on email
     * - Make email nullable
     * - Convert any empty string emails to NULL so unique index removal is clean
     */
    public function up(): void
    {
        // Convert any empty string emails to NULL first
        DB::table('patients')->where('email', '')->update(['email' => null]);

        Schema::table('patients', function (Blueprint $table) {
            // Drop the unique index on email
            $table->dropUnique(['email']);
            // Make email nullable (change the column)
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->unique('email');
        });
    }
};
