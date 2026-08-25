<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add uuid column to users table if not exists
        if (! Schema::hasColumn('users', 'uuid')) {
            Schema::table('users', function (Blueprint $table) {
                $table->uuid('uuid')->default(DB::raw('(UUID())'))->unique()->after('id');
            });
        }

        // 2. Add uuid column to vendors table if not exists
        if (! Schema::hasColumn('vendors', 'uuid')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->uuid('uuid')->default(DB::raw('(UUID())'))->unique()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'uuid')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['uuid']);
                $table->dropColumn('uuid');
            });
        }

        if (Schema::hasColumn('vendors', 'uuid')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropUnique(['uuid']);
                $table->dropColumn('uuid');
            });
        }
    }
};
