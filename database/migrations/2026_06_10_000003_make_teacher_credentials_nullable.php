<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeTeacherCredentialsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            ALTER TABLE teachers 
            MODIFY COLUMN email VARCHAR(255) NULL,
            MODIFY COLUMN password VARCHAR(255) NULL;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            UPDATE teachers 
            SET email = CONCAT('placeholder_', id, '@example.com') 
            WHERE email IS NULL;
        ");

        DB::statement("
            UPDATE teachers 
            SET password = 'placeholder_hash' 
            WHERE password IS NULL;
        ");

        // 2. MySQL syntax to re-enforce NOT NULL
        DB::statement('
            ALTER TABLE teachers 
            MODIFY COLUMN email VARCHAR(255) NOT NULL,
            MODIFY COLUMN password VARCHAR(255) NOT NULL;
        ');
    }
}
