<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollEmployeeTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER tbl_payroll_employee_search_insert_trigger AFTER INSERT ON `tbl_payroll_employee_basic` FOR EACH ROW
                        BEGIN
                            INSERT INTO tbl_payroll_employee_search (`payroll_search_employee_id`, `body`) 
                            VALUES (NEW.payroll_employee_id, 
                            CONCAT(NEW.payroll_employee_title_name,' ', NEW.payroll_employee_first_name,' ', NEW.payroll_employee_middle_name,' ', NEW.payroll_employee_last_name,' ', NEW.payroll_employee_suffix_name,' ', NEW.payroll_employee_display_name,' ', NEW.payroll_employee_email,' ', NEW.payroll_employee_number));
                        END;
            ");

        DB::unprepared("
            CREATE TRIGGER tbl_payroll_employee_search_update_trigger AFTER UPDATE ON `tbl_payroll_employee_basic` FOR EACH ROW
                        BEGIN
                            UPDATE tbl_payroll_employee_search SET `body` =  CONCAT(NEW.payroll_employee_title_name,' ', NEW.payroll_employee_first_name,' ', NEW.payroll_employee_middle_name,' ', NEW.payroll_employee_last_name,' ', NEW.payroll_employee_suffix_name,' ', NEW.payroll_employee_display_name,' ', NEW.payroll_employee_email,' ', NEW.payroll_employee_number)
                            WHERE `payroll_search_employee_id` = NEW.payroll_employee_id;
                        END;
            ");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tbl_payroll_employee_search_insert_trigger`');
        DB::unprepared('DROP TRIGGER `tbl_payroll_employee_search_update_trigger`');
    }
}
