<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->after('address');
            $table->date('dob')->nullable()->after('gender');
            $table->enum('user_status', ['Active', 'On Leave', 'Suspended', 'Terminated'])->default('Active')->after('profile_photo_path');
            $table->date('hire_date')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'gender',
                'dob',
                'status',
                'hire_date'
            ]);
        });
    }
};