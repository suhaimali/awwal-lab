<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_path');
            $table->string('pin_hash');
            $table->timestamps();
        });

        Schema::table('test_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('test_reports', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }

            if (!Schema::hasColumn('test_reports', 'report_signature_id')) {
                $table->foreignId('report_signature_id')
                    ->nullable()
                    ->after('notes')
                    ->constrained('report_signatures')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('test_reports', function (Blueprint $table) {
            if (Schema::hasColumn('test_reports', 'report_signature_id')) {
                $table->dropConstrainedForeignId('report_signature_id');
            }

            if (Schema::hasColumn('test_reports', 'notes')) {
                $table->dropColumn('notes');
            }
        });

        Schema::dropIfExists('report_signatures');
    }
};
