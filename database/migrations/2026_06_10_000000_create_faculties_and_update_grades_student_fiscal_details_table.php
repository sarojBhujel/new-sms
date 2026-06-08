<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('faculties')) {
            Schema::create('faculties', function (Blueprint $table) {
                $table->id();
                $table->string('faculty_name');
                $table->string('faculty_code')->unique();
                $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->index('faculty_code');
                $table->index('grade_id');
            });
        }

        if (Schema::hasTable('grades') && !Schema::hasColumn('grades', 'has_faculty')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->boolean('has_faculty')->default(false)->after('Notes');
            });
        }

        if (Schema::hasTable('student_fiscal_details') && !Schema::hasColumn('student_fiscal_details', 'faculty_id')) {
            Schema::table('student_fiscal_details', function (Blueprint $table) {
                $table->unsignedBigInteger('faculty_id')->nullable()->after('academic_year_id');
                $table->foreign('faculty_id')->references('id')->on('faculties')->nullOnDelete();
                $table->index('faculty_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('student_fiscal_details') && Schema::hasColumn('student_fiscal_details', 'faculty_id')) {
            Schema::table('student_fiscal_details', function (Blueprint $table) {
                $table->dropForeign(['faculty_id']);
                $table->dropIndex(['faculty_id']);
                $table->dropColumn('faculty_id');
            });
        }

        if (Schema::hasTable('grades') && Schema::hasColumn('grades', 'has_faculty')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->dropColumn('has_faculty');
            });
        }

        Schema::dropIfExists('faculties');
    }
};
