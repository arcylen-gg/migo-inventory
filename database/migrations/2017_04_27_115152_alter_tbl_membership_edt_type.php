<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMembershipEdtType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_membership', function (Blueprint $table) {
            //
            // $table->renameColumn('membership_type', 'membership_type_a');
            if(schema::hasColumn('tbl_membership','membership_type'))
            {
                $table->renameColumn('membership_type', 'membership_type_a');
            }
            else
            {
                $table->integer("membership_type_a")->default(0);
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
        Schema::table('tbl_membership', function (Blueprint $table) {
            //
        });
    }
}
