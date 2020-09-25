<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnTblItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('tbl_item', 'item_unilevel_points'))
        {
            Schema::table('tbl_item', function (Blueprint $table)
            {

                $table->dropColumn(['item_unilevel_points']);
            });
        }

        if (Schema::hasColumn('tbl_item', 'item_binary_points'))
        {
            Schema::table('tbl_item', function (Blueprint $table)
            {

                $table->dropColumn(['item_binary_points']);
            });
        }

        if (Schema::hasColumn('tbl_item', 'item_upgrade_points'))
        {
            Schema::table('tbl_item', function (Blueprint $table)
            {

                $table->dropColumn(['item_upgrade_points']);
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
