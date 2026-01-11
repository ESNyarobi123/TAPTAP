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
            // Make order_id nullable for quick payments without orders
            $table->foreignId('order_id')->nullable()->change();
            
            // Add restaurant_id for quick payments (reference to restaurant)
            $table->foreignId('restaurant_id')->nullable()->after('order_id')->constrained()->onDelete('cascade');
            
            // Add customer phone for quick payments
            $table->string('customer_phone')->nullable()->after('restaurant_id');
            
            // Add description/comment for what the payment is for
            $table->text('description')->nullable()->after('transaction_reference');
            
            // Add payment type to distinguish order payments from quick payments
            $table->string('payment_type')->default('order')->after('method'); // order, quick
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['restaurant_id']);
            $table->dropColumn(['restaurant_id', 'customer_phone', 'description', 'payment_type']);
        });
    }
};
