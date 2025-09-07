<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndPermissionsToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('admins', 'status')) {
                $table->tinyInteger('status')->default(1)->after('password');
            }
            
            // Add permissions column if it doesn't exist
            if (!Schema::hasColumn('admins', 'permissions')) {
                $table->json('permissions')->nullable()->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('admins', 'permissions')) {
                $table->dropColumn('permissions');
            }
        });
    }
}
