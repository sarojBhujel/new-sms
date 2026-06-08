<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('student_fiscal_details')) {
            Schema::table('student_fiscal_details', function (Blueprint $table) {
                if (!Schema::hasColumn('student_fiscal_details', 'active_fiscal_year_id')) {
                    $table->unsignedBigInteger('active_fiscal_year_id')->nullable()->after('academic_year_id');
                    $table->foreign('active_fiscal_year_id')->references('id')->on('fiscal_years')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('fees')) {
            Schema::table('fees', function (Blueprint $table) {
                if (!Schema::hasColumn('fees', 'active_fiscal_year_id')) {
                    $table->unsignedBigInteger('active_fiscal_year_id')->nullable()->after('Fee_type');
                    $table->foreign('active_fiscal_year_id')->references('id')->on('fiscal_years')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('fee_invoices')) {
            Schema::table('fee_invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('fee_invoices', 'active_fiscal_year_id')) {
                    $table->unsignedBigInteger('active_fiscal_year_id')->nullable()->after('description');
                    $table->foreign('active_fiscal_year_id')->references('id')->on('fiscal_years')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('receipt_students')) {
            Schema::table('receipt_students', function (Blueprint $table) {
                if (!Schema::hasColumn('receipt_students', 'active_fiscal_year_id')) {
                    $table->unsignedBigInteger('active_fiscal_year_id')->nullable()->after('description');
                    $table->foreign('active_fiscal_year_id')->references('id')->on('fiscal_years')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('fund_accounts')) {
            Schema::table('fund_accounts', function (Blueprint $table) {
                if (!Schema::hasColumn('fund_accounts', 'active_fiscal_year_id')) {
                    $table->unsignedBigInteger('active_fiscal_year_id')->nullable()->after('description');
                    $table->foreign('active_fiscal_year_id')->references('id')->on('fiscal_years')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('processing_fees')) {
            Schema::table('processing_fees', function (Blueprint $table) {
                if (!Schema::hasColumn('processing_fees', 'active_fiscal_year_id')) {
                    $table->unsignedBigInteger('active_fiscal_year_id')->nullable()->after('description');
                    $table->foreign('active_fiscal_year_id')->references('id')->on('fiscal_years')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('exams')) {
            Schema::table('exams', function (Blueprint $table) {
                if (!Schema::hasColumn('exams', 'active_fiscal_year_id')) {
                    $table->unsignedBigInteger('active_fiscal_year_id')->nullable()->after('academic_year');
                    $table->foreign('active_fiscal_year_id')->references('id')->on('fiscal_years')->onDelete('set null');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('student_fiscal_details')) {
            Schema::table('student_fiscal_details', function (Blueprint $table) {
                if (Schema::hasColumn('student_fiscal_details', 'active_fiscal_year_id')) {
                    $table->dropForeign(['active_fiscal_year_id']);
                    $table->dropColumn('active_fiscal_year_id');
                }
            });
        }

        if (Schema::hasTable('fees')) {
            Schema::table('fees', function (Blueprint $table) {
                if (Schema::hasColumn('fees', 'active_fiscal_year_id')) {
                    $table->dropForeign(['active_fiscal_year_id']);
                    $table->dropColumn('active_fiscal_year_id');
                }
            });
        }

        if (Schema::hasTable('fee_invoices')) {
            Schema::table('fee_invoices', function (Blueprint $table) {
                if (Schema::hasColumn('fee_invoices', 'active_fiscal_year_id')) {
                    $table->dropForeign(['active_fiscal_year_id']);
                    $table->dropColumn('active_fiscal_year_id');
                }
            });
        }

        if (Schema::hasTable('receipt_students')) {
            Schema::table('receipt_students', function (Blueprint $table) {
                if (Schema::hasColumn('receipt_students', 'active_fiscal_year_id')) {
                    $table->dropForeign(['active_fiscal_year_id']);
                    $table->dropColumn('active_fiscal_year_id');
                }
            });
        }

        if (Schema::hasTable('fund_accounts')) {
            Schema::table('fund_accounts', function (Blueprint $table) {
                if (Schema::hasColumn('fund_accounts', 'active_fiscal_year_id')) {
                    $table->dropForeign(['active_fiscal_year_id']);
                    $table->dropColumn('active_fiscal_year_id');
                }
            });
        }

        if (Schema::hasTable('processing_fees')) {
            Schema::table('processing_fees', function (Blueprint $table) {
                if (Schema::hasColumn('processing_fees', 'active_fiscal_year_id')) {
                    $table->dropForeign(['active_fiscal_year_id']);
                    $table->dropColumn('active_fiscal_year_id');
                }
            });
        }

        if (Schema::hasTable('exams')) {
            Schema::table('exams', function (Blueprint $table) {
                if (Schema::hasColumn('exams', 'active_fiscal_year_id')) {
                    $table->dropForeign(['active_fiscal_year_id']);
                    $table->dropColumn('active_fiscal_year_id');
                }
            });
        }
    }
};
