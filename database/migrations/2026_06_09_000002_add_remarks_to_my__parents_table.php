<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('my__parents', function (Blueprint $table) {
            if (!Schema::hasColumn('my__parents', 'remarks')) {
                $table->text('remarks')->nullable()->after('Address_Mother');
            }
        });
    }

    public function down()
    {
        Schema::table('my__parents', function (Blueprint $table) {
            if (Schema::hasColumn('my__parents', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};
