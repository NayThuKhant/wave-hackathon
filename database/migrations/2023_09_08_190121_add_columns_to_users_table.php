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
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('dob')->nullable()->after('name');
            $table->string('nrc')->nullable()->after('dob');
            $table->string('gender')->nullable()->after('nrc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->change();
            $table->string('password')->change();
            $table->dropColumn('dob');
            $table->dropColumn('nrc');
            $table->dropColumn('gender');
        });
    }
};
