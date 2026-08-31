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
        // First remove staff_id foreign key
        Schema::table('staff_permissions', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
        });

        // Now remove old unique index and column
        Schema::table('staff_permissions', function (Blueprint $table) {
            $table->dropUnique('staff_permissions_staff_id_permission_key_unique');
            $table->dropColumn('permission_key');
        });

        // Add permission_id and recreate foreign keys
        Schema::table('staff_permissions', function (Blueprint $table) {
            $table->foreignId('permission_id')
                ->after('staff_id')
                ->constrained('permissions')
                ->cascadeOnDelete();

            $table->foreign('staff_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->unique(['staff_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_permissions', function (Blueprint $table) {
            $table->dropForeign(['permission_id']);
            $table->dropForeign(['staff_id']);

            $table->dropUnique('staff_permissions_staff_id_permission_id_unique');

            $table->dropColumn('permission_id');
        });

        Schema::table('staff_permissions', function (Blueprint $table) {
            $table->string('permission_key')
                ->after('staff_id');

            $table->unique(['staff_id', 'permission_key']);

            $table->foreign('staff_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
