<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fees', function (Blueprint $table) {
            //  $table->dropForeign(['Grade_id']);
            //   $table->dropColumn('Grade_id');
            // $table->foreignId('fee_name_id')->references('id')->on('fee_names');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fees', function (Blueprint $table) {
             $table->dropForeign(['Grade_id']);
              $table->dropColumn('Grade_id');
        });
    }
};
