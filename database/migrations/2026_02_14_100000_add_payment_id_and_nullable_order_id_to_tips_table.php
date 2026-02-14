<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * - payment_id: link tip to quick payment (avoid duplicate when polling)
     * - order_id nullable: tips from quick payment have no order
     */
    public function up(): void
    {
        Schema::table('tips', function (Blueprint $table) {
            $table->foreignId('payment_id')->nullable()->after('order_id')->constrained('payments')->onDelete('set null');
        });

        Schema::table('tips', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tips', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
        });
        Schema::table('tips', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable(false)->change();
        });
    }
};
