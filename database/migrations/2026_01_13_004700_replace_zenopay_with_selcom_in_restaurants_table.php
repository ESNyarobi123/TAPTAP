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
        Schema::table('restaurants', function (Blueprint $table) {
            // Add Selcom fields
            $table->string('selcom_vendor_id')->nullable()->after('logo');
            $table->string('selcom_api_key')->nullable()->after('selcom_vendor_id');
            $table->string('selcom_api_secret')->nullable()->after('selcom_api_key');
            $table->boolean('selcom_is_live')->default(false)->after('selcom_api_secret');
            
            // Drop ZenoPay field if it exists
            if (Schema::hasColumn('restaurants', 'zenopay_api_key')) {
                $table->dropColumn('zenopay_api_key');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // Re-add ZenoPay field
            $table->string('zenopay_api_key')->nullable()->after('logo');
            
            // Drop Selcom fields
            $table->dropColumn([
                'selcom_vendor_id',
                'selcom_api_key', 
                'selcom_api_secret',
                'selcom_is_live'
            ]);
        });
    }
};
