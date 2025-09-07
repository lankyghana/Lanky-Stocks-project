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
        if (!Schema::hasColumn('admins', 'status')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->after('role');
            });
        }
        
        if (!Schema::hasColumn('admins', 'permissions')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->json('permissions')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['status', 'permissions']);
        });
    }
};
