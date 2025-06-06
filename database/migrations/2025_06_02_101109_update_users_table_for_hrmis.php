<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('employee_id')->unique()->after('last_name');
            $table->string('department')->after('employee_id');
            $table->string('role')->default('employee')->after('department');
            $table->dropColumn('name'); // Remove the default name column
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'employee_id', 'department', 'role']);
            $table->string('name')->after('id');
        });
    }
};