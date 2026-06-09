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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'patient_id')) {
                $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('payments', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('advance_paid', 10, 2)->default(0);
                $table->decimal('net_amount', 10, 2)->default(0);
                $table->decimal('balance_due', 10, 2)->default(0);
                $table->string('payment_status')->default('Unpaid');
            }
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('payments', 'bill_date')) {
                $table->date('bill_date')->nullable();
                $table->text('remarks')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropColumn([
                'patient_id', 'total_amount', 'discount', 'advance_paid',
                'net_amount', 'balance_due', 'payment_status', 'payment_method',
                'bill_date', 'remarks'
            ]);
        });
    }
};
