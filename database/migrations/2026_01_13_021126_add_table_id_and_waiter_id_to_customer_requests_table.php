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
        Schema::table('customer_requests', function (Blueprint $table) {
            // Make table_number nullable (for waiter-based requests)
            $table->string('table_number')->nullable()->change();

            // Add table_id and waiter_id
            $table->foreignId('table_id')->nullable()->after('table_number')->constrained()->nullOnDelete();
            $table->foreignId('waiter_id')->nullable()->after('table_id')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_requests', function (Blueprint $table) {
            $table->dropForeign(['table_id']);
            $table->dropForeign(['waiter_id']);
            $table->dropColumn(['table_id', 'waiter_id']);
            $table->string('table_number')->nullable(false)->change();
        });
    }
};
