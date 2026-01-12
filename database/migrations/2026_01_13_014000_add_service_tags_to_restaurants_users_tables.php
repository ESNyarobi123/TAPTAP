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
        // Add tag_prefix to restaurants
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('tag_prefix', 10)->unique()->nullable()->after('name');
        });

        // Add waiter_code to users (for waiters)
        Schema::table('users', function (Blueprint $table) {
            $table->string('waiter_code', 20)->unique()->nullable()->after('email');
        });

        // Add table_tag to tables
        Schema::table('tables', function (Blueprint $table) {
            $table->string('table_tag', 20)->unique()->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('tag_prefix');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('waiter_code');
        });

        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn('table_tag');
        });
    }
};
