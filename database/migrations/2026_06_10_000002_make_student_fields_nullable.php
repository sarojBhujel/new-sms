<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Schema::table('students', function (Blueprint $table) {
        //     if (Schema::hasColumn('students', 'phone')) {
        //         $table->string('phone')->nullable()->change();
        //     }

        //     if (Schema::hasColumn('students', 'blood_id')) {
        //         $table->unsignedBigInteger('blood_id')->nullable()->change();
        //     }

        //     if (Schema::hasColumn('students', 'section_id')) {
        //         $table->unsignedBigInteger('section_id')->nullable()->change();
        //     }
        // });
        DB::statement("ALTER TABLE students MODIFY phone VARCHAR(255) NULL");
    DB::statement("ALTER TABLE students MODIFY blood_id BIGINT UNSIGNED NULL");
    DB::statement("ALTER TABLE students MODIFY section_id BIGINT UNSIGNED NULL");
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable(false)->change();
            }

            if (Schema::hasColumn('students', 'blood_id')) {
                $table->unsignedBigInteger('blood_id')->nullable(false)->change();
            }

            if (Schema::hasColumn('students', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable(false)->change();
            }
        });
    }
};
