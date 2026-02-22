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
        Schema::table('users', function (Blueprint $table) {
            $table->string('employment_type', 20)->nullable()->after('waiter_code')->comment('permanent or temporary (show-time)');
            $table->date('linked_until')->nullable()->after('employment_type')->comment('For temporary: link ends on this date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employment_type', 'linked_until']);
        });
    }
};
