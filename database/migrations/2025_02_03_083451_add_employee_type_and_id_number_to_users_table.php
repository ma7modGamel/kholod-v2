<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_type')->nullable(); // employee_type: admin_employee أو site_employee
            $table->string('id_number')->nullable()->after('employee_type'); // رقم الهوية
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_type', 'id_number']);
        });
    }
};
