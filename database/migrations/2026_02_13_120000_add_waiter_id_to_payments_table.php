<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Used when quick payment is for a waiter tip - allows creating Tip when payment is confirmed.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'waiter_id')) {
                $table->foreignId('waiter_id')->nullable()->after('restaurant_id')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'waiter_id')) {
                $table->dropForeign(['waiter_id']);
                $table->dropColumn('waiter_id');
            }
        });
    }
};