<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement("
                ALTER TABLE my__parents 
                MODIFY COLUMN email VARCHAR(255) NULL,
                MODIFY COLUMN password VARCHAR(255) NULL,
                MODIFY COLUMN National_ID_Father VARCHAR(255) NULL,
                MODIFY COLUMN Passport_ID_Father VARCHAR(255) NULL,
                MODIFY COLUMN Phone_Father VARCHAR(255) NULL,
                MODIFY COLUMN Job_Father VARCHAR(255) NULL,
                MODIFY COLUMN Nationality_Father_id BIGINT UNSIGNED NULL,
                MODIFY COLUMN Blood_Type_Father_id BIGINT UNSIGNED NULL,
                MODIFY COLUMN Religion_Mother_id BIGINT UNSIGNED NULL,
                MODIFY COLUMN Religion_Father_id BIGINT UNSIGNED NULL,
                MODIFY COLUMN Address_Father VARCHAR(255) NULL,
                MODIFY COLUMN National_ID_Mother VARCHAR(255) NULL,
                MODIFY COLUMN Passport_ID_Mother VARCHAR(255) NULL,
                MODIFY COLUMN Phone_Mother VARCHAR(255) NULL,
                MODIFY COLUMN Job_Mother VARCHAR(255) NULL,
                MODIFY COLUMN Nationality_Mother_id BIGINT UNSIGNED NULL,
                MODIFY COLUMN Blood_Type_Mother_id BIGINT UNSIGNED NULL,
                MODIFY COLUMN Address_Mother VARCHAR(255) NULL
                ");
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my__parents', function (Blueprint $table) {
            //
        });
    }
};
