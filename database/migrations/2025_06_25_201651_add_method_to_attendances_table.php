<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('method')->default('Manual')->after('status'); // or 'check_in' if preferred
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('method');
        });
    }
    
};
