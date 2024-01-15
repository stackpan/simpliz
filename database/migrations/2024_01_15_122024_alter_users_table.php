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
            $table->ulid('id')->change();
            $table->string('name', 50)->change();
            $table->string('email', 100)->change();
            $table->ulid('accountable_id');
            $table->string('accountable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->id()->change();
            $table->string('name')->change();
            $table->string('email')->unique()->change();
            $table->dropColumn('accountable_id');
            $table->dropColumn('accountable_type');
        });
    }
};
